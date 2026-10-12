<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\KMeansRun;
use App\Models\Penduduk;
use App\Services\KMeansKerawananService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class AnalisisKMeansController extends Controller
{
    /**
     * Halaman analisis K-Means.
     */
    public function index(Request $request)
    {
        $jenis = $request->input('jenis', 'penyelamatan');

        /*
         * Opsi periode untuk form proses (tetap).
         */
        $periodeOptions = $this->susunOpsiPeriode(
            $this->tahunTersedia()
        );

        /*
         * Default: seluruh rentang (opsi terakhir).
         */
        $periodeForm = $request->input(
            'periode',
            $periodeOptions->last()['value'] ?? null
        );

        /*
         * Opsi filter riwayat: opsi tetap
         * + rentang lain yang pernah diproses.
         */
        $pernahDiproses = KMeansRun::query()
            ->whereNotNull('tahun_awal')
            ->select('tahun_awal', 'tahun_akhir')
            ->distinct()
            ->get()
            ->map(fn ($r) => $r->tahun_awal . '/' . $r->tahun_akhir)
            ->all();

        $filterOptions = $this->susunOpsiPeriode(
            $this->tahunTersedia(),
            $pernahDiproses
        );

        /*
         * Riwayat proses (filter periode + pagination).
         */
        $filterPeriode = $request->input('filter_periode');

        $runs = KMeansRun::with('user')
            ->when($filterPeriode, function ($query) use ($filterPeriode) {

                $bagian = explode('/', $filterPeriode);

                $awal  = (int) $bagian[0];
                $akhir = (int) ($bagian[1] ?? $bagian[0]);

                $query->where('tahun_awal', $awal)
                      ->where('tahun_akhir', $akhir);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $title = 'Analisis K-Means';

        return view(
            'admin.analisis-kmeans.index',
            compact(
                'jenis',
                'periodeOptions',
                'periodeForm',
                'filterOptions',
                'filterPeriode',
                'runs',
                'title'
            )
        );
    }


    /**
     * Jalankan proses K-Means.
     */
    public function proses(
        Request $request,
        KMeansKerawananService $service
    ) {

        $allowed = $this->susunOpsiPeriode(
            $this->tahunTersedia()
        )->pluck('value')->all();

        $validated = $request->validate(
            [
                'jenis' => [
                    'required',
                    'in:penyelamatan,kebakaran',
                ],

                'periode' => [
                    'required',
                    Rule::in($allowed),
                ],
            ],
            [
                'periode.required' => 'Periode wajib dipilih.',
                'periode.in' => 'Periode yang dipilih tidak valid.',
            ]
        );

        [$tahunAwal, $tahunAkhir] = array_map(
            'intval',
            explode('/', $validated['periode'])
        );

        try {

            $run = $service->proses(
                $validated['jenis'],
                $tahunAwal,
                $tahunAkhir,
                auth()->id()
            );

        } catch (InvalidArgumentException $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'analisis' => $e->getMessage(),
                ]);
        }

        ActivityLogger::log(
            'Proses K-Means',
            'Menjalankan analisis K-Means '
                . $validated['jenis']
                . ' periode '
                . $run->rentang,
            'Analisis K-Means',
            'process'
        );

        return redirect()
            ->route('analisis-kmeans.show', $run)
            ->with(
                'success',
                'Proses K-Means berhasil dilakukan. '
                . 'Silhouette: ' . $run->silhouette
                . ', Davies-Bouldin: ' . $run->davies_bouldin
                . '. Hasil telah disimpan dan dapat '
                . 'ditampilkan pada Peta Kerawanan.'
            );
    }


    /**
     * Detail hasil sebuah proses K-Means.
     */
    public function show(Request $request, KMeansRun $kmeansRun)
    {
        $kmeansRun->load('user');

        $search = trim((string) $request->input('search'));
        $status = $request->input('status');

        $warnaTingkat = [
            'Tinggi' => '#ef4444',
            'Sedang' => '#eab308',
            'Rendah' => '#22c55e',
        ];

        /*
         * Seluruh hasil (tanpa filter) untuk
         * ringkasan, profil klaster, dan grafik.
         */
        $semua = $kmeansRun->results()
            ->with('kelurahan')
            ->get();

        $totalKejadian   = $semua->sum('jumlah_kejadian');
        $jumlahKelurahan = $semua->count();

        $profil = collect(['Tinggi', 'Sedang', 'Rendah'])
            ->map(function ($tingkat) use ($semua) {

                $grup = $semua->where('tingkat_kerawanan', $tingkat);

                return [
                    'tingkat'    => $tingkat,
                    'jumlah'     => $grup->count(),
                    'frekuensi'  => $grup->avg('jumlah_kejadian') ?? 0,
                    'kepadatan'  => $grup->avg(
                        fn ($r) => (float) $r->kepadatan_penduduk
                    ) ?? 0,
                    'silhouette' => $grup->avg(
                        fn ($r) => (float) $r->silhouette_kelurahan
                    ) ?? 0,
                ];
            });

        $dataGrafik = collect(['Rendah', 'Sedang', 'Tinggi'])
            ->map(function ($tingkat) use ($semua, $warnaTingkat) {

                return [
                    'label' => $tingkat,
                    'backgroundColor' => $warnaTingkat[$tingkat],
                    'data' => $semua
                        ->where('tingkat_kerawanan', $tingkat)
                        ->map(fn ($r) => [
                            'x' => (int) $r->jumlah_kejadian,
                            'y' => (float) $r->kepadatan_penduduk,
                            'nama' => $r->kelurahan->nama ?? '-',
                        ])
                        ->values(),
                ];
            })
            ->values();

        /*
         * Tabel hasil: search + status + pagination.
         */
        $hasil = $kmeansRun->results()
            ->with('kelurahan.kecamatan')
            ->when($search !== '', function ($query) use ($search) {

                $query->whereHas('kelurahan', function ($k) use ($search) {

                    $k->where('nama', 'like', "%{$search}%")
                      ->orWhereHas('kecamatan', function ($c) use ($search) {
                          $c->where('nama', 'like', "%{$search}%");
                      });
                });
            })
            ->when(
                in_array($status, ['Tinggi', 'Sedang', 'Rendah'], true),
                fn ($query) => $query->where('tingkat_kerawanan', $status)
            )
            ->orderByRaw("FIELD(tingkat_kerawanan, 'Tinggi', 'Sedang', 'Rendah')")
            ->orderByDesc('jumlah_kejadian')
            ->orderBy('kelurahan_id')
            ->paginate(15)
            ->withQueryString();

        $title = 'Detail Hasil K-Means';

        return view(
            'admin.analisis-kmeans.show',
            compact(
                'kmeansRun',
                'hasil',
                'profil',
                'dataGrafik',
                'totalKejadian',
                'jumlahKelurahan',
                'warnaTingkat',
                'search',
                'status',
                'title'
            )
        );
    }


    /**
     * Daftar tahun yang tersedia di data penduduk.
     */
    protected function tahunTersedia()
    {
        return Penduduk::query()
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun')
            ->pluck('tahun');
    }


    /**
     * Susun opsi periode:
     * pasangan 2 tahun berurutan + seluruh rentang,
     * ditambah rentang lain yang pernah diproses (opsional).
     *
     * @param  \Illuminate\Support\Collection  $tahunList
     * @param  array  $tambahan  contoh: ['2023/2023']
     */
    protected function susunOpsiPeriode($tahunList, array $tambahan = [])
    {
        $tahun = collect($tahunList)
            ->map(fn ($t) => (int) $t)
            ->unique()
            ->sort()
            ->values();

        $opsi = [];

        if ($tahun->isNotEmpty()) {

            for ($i = 0; $i < $tahun->count() - 1; $i++) {

                $awal  = $tahun[$i];
                $akhir = $tahun[$i + 1];

                $opsi["{$awal}/{$akhir}"] = "{$awal}/{$akhir} ("
                    . ($akhir - $awal + 1) . ' tahun)';
            }

            $min = $tahun->first();
            $max = $tahun->last();

            if ($min !== $max) {
                $opsi["{$min}/{$max}"] = "{$min}/{$max} (semua)";
            }
        }

        foreach ($tambahan as $value) {

            if (isset($opsi[$value])) {
                continue;
            }

            [$awal, $akhir] = array_map('intval', explode('/', $value));

            $opsi[$value] = $awal === $akhir
                ? (string) $awal
                : "{$awal}/{$akhir} (" . ($akhir - $awal + 1) . ' tahun)';
        }

        return collect($opsi)
            ->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->values();
    }
}