<?php

namespace App\Http\Controllers;

use App\Models\KMeansRun;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PetaKerawananController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Filter: jenis kejadian
        |--------------------------------------------------------------------------
        */

        $jenis = $request->input('jenis', 'penyelamatan');

        if (! in_array($jenis, ['penyelamatan', 'kebakaran'], true)) {
            $jenis = 'penyelamatan';
        }

        /*
        |--------------------------------------------------------------------------
        | Opsi periode
        |--------------------------------------------------------------------------
        |
        | - Opsi tetap: pasangan 2 tahun berurutan + seluruh rentang
        | - Ditambah rentang lain yang pernah diproses
        | - Setiap opsi diberi penanda sudah/belum dianalisis
        |
        */

        $tahunList = Penduduk::query()
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun')
            ->pluck('tahun');

        $sudahDianalisis = KMeansRun::query()
            ->where('jenis_kejadian', $jenis)
            ->whereNotNull('tahun_awal')
            ->select('tahun_awal', 'tahun_akhir')
            ->distinct()
            ->get()
            ->map(fn ($r) => $r->tahun_awal . '/' . $r->tahun_akhir)
            ->all();

        $periodeList = $this->susunOpsiPeriode(
            $tahunList,
            $sudahDianalisis
        );

        /*
        |--------------------------------------------------------------------------
        | Periode terpilih
        |--------------------------------------------------------------------------
        |
        | Kalau tidak valid, pakai periode terbaru yang sudah dianalisis
        | (akhir terbesar, lalu rentang terpanjang).
        |
        */

        $periode = $request->input('periode');

        if (! $periodeList->contains('value', $periode)) {

            $default = $periodeList
                ->where('tersedia', true)
                ->sortByDesc(function ($o) {

                    [$awal, $akhir] = array_map(
                        'intval',
                        explode('/', $o['value'])
                    );

                    return sprintf('%04d%04d', $akhir, $akhir - $awal);
                })
                ->first();

            $periode = $default['value']
                ?? $periodeList->last()['value']
                ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | Cari hasil K-Means
        |--------------------------------------------------------------------------
        |
        | Peta TIDAK menghitung K-Means.
        | Peta hanya mengambil hasil yang sudah disimpan.
        |
        */

        $kmeansRun = null;

        if ($periode) {

            [$awal, $akhir] = array_map(
                'intval',
                explode('/', $periode)
            );

            $kmeansRun = KMeansRun::query()
                ->with([
                    'user',
                    'results.kelurahan.kecamatan',
                ])
                ->where('jenis_kejadian', $jenis)
                ->where('tahun_awal', $awal)
                ->where('tahun_akhir', $akhir)
                ->latest('id')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Bentuk data untuk JavaScript
        |--------------------------------------------------------------------------
        */

        $hasilKerawanan = [];

        if ($kmeansRun) {

            foreach ($kmeansRun->results as $result) {

                $kelurahan = $result->kelurahan;

                if (! $kelurahan) {
                    continue;
                }

                $namaKelurahan = mb_strtolower(
                    trim($kelurahan->nama)
                );

                $namaKecamatan = mb_strtolower(
                    trim($kelurahan->kecamatan?->nama ?? '')
                );

                $key = $namaKelurahan . '|' . $namaKecamatan;

                $hasilKerawanan[$key] = [
                    'kelurahan_id' => $result->kelurahan_id,

                    'kelurahan' => $kelurahan->nama,

                    'kecamatan' => $kelurahan->kecamatan?->nama,

                    // Variabel input K-Means
                    'jumlah_kejadian' => (int) $result->jumlah_kejadian,

                    'kepadatan_penduduk' => (float) $result->kepadatan_penduduk,

                    'silhouette_kelurahan' =>
                        $result->silhouette_kelurahan !== null
                            ? (float) $result->silhouette_kelurahan
                            : null,

                    // Informasi pendukung
                    'jumlah_korban' => (int) $result->jumlah_korban,

                    'total_kerugian' => (float) $result->total_kerugian,

                    // Hasil
                    'cluster' => $result->cluster,

                    'tingkat_kerawanan' => $result->tingkat_kerawanan,

                    'warna' => $result->warna,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil boundary Tangerang Selatan
        |--------------------------------------------------------------------------
        */

        $boundaryTangsel = [];

        try {

            $response = Http::timeout(30)
                ->get(
                    'https://wilayah.smartartstudio.my.id/api/boundaries/36.74/children'
                );

            if ($response->successful()) {

                $dataBoundary = collect($response->json());

                /*
                |--------------------------------------------------------------------------
                | Ambil Kecamatan
                |--------------------------------------------------------------------------
                */

                $dataKecamatan = $dataBoundary
                    ->filter(function ($item) {

                        return preg_match(
                            '/^36\.74\.\d{2}$/',
                            $item['kode'] ?? ''
                        );
                    })
                    ->keyBy('kode');

                /*
                |--------------------------------------------------------------------------
                | Ambil Kelurahan
                |--------------------------------------------------------------------------
                */

                $boundaryTangsel = $dataBoundary
                    ->filter(function ($item) {

                        return preg_match(
                            '/^36\.74\.\d{2}\.\d{4}$/',
                            $item['kode'] ?? ''
                        );
                    })
                    ->map(function ($item) use ($dataKecamatan) {

                        $kodeKelurahan = $item['kode'];

                        $parts = explode('.', $kodeKelurahan);

                        $kodeKecamatan = implode(
                            '.',
                            array_slice($parts, 0, 3)
                        );

                        $namaKecamatan =
                            $dataKecamatan[$kodeKecamatan]['nama']
                            ?? '-';

                        return [
                            'kode' => $item['kode'],

                            'nama' => $item['nama'],

                            'kecamatan' => $namaKecamatan,

                            'lat' => $item['lat'] ?? null,

                            'lng' => $item['lng'] ?? null,

                            'path' => $item['path'] ?? [],
                        ];
                    })
                    ->values()
                    ->toArray();
            }

        } catch (\Throwable $e) {

            report($e);

            $boundaryTangsel = [];
        }

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $statistik = [
            'total_kelurahan' => count($boundaryTangsel),

            'total_hasil' => count($hasilKerawanan),

            'rendah' => 0,

            'sedang' => 0,

            'tinggi' => 0,
        ];

        foreach ($hasilKerawanan as $hasil) {

            match ($hasil['tingkat_kerawanan']) {

                'Rendah' => $statistik['rendah']++,

                'Sedang' => $statistik['sedang']++,

                'Tinggi' => $statistik['tinggi']++,

                default => null,
            };
        }

        $title = 'Peta Kerawanan';

        return view(
            'admin.peta-kerawanan.index',
            compact(
                'jenis',
                'periode',
                'periodeList',
                'kmeansRun',
                'hasilKerawanan',
                'boundaryTangsel',
                'statistik',
                'title'
            )
        );
    }


    /**
     * Susun daftar opsi periode.
     *
     * @param  \Illuminate\Support\Collection  $tahunList  tahun pada data penduduk
     * @param  array  $sudahDianalisis  contoh: ['2022/2023', '2022/2025']
     */
    protected function susunOpsiPeriode($tahunList, array $sudahDianalisis)
    {
        $tahun = collect($tahunList)
            ->map(fn ($t) => (int) $t)
            ->unique()
            ->sort()
            ->values();

        $opsi = [];

        if ($tahun->isNotEmpty()) {

            // Pasangan 2 tahun berurutan
            for ($i = 0; $i < $tahun->count() - 1; $i++) {

                $awal  = $tahun[$i];
                $akhir = $tahun[$i + 1];

                $opsi["{$awal}/{$akhir}"] = "{$awal}/{$akhir} ("
                    . ($akhir - $awal + 1) . ' tahun)';
            }

            // Seluruh rentang
            $min = $tahun->first();
            $max = $tahun->last();

            if ($min !== $max) {
                $opsi["{$min}/{$max}"] = "{$min}/{$max}";
            }
        }

        // Rentang lain yang pernah diproses (mis. dari versi lama)
        foreach ($sudahDianalisis as $value) {

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
                'value'    => $value,
                'label'    => $label,
                'tersedia' => in_array($value, $sudahDianalisis, true),
            ])
            ->values();
    }
}