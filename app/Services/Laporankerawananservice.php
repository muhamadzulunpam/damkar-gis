<?php

namespace App\Services;

use App\Models\KMeansRun;
use App\Models\Penduduk;
use Illuminate\Support\Collection;

/**
 * Mengumpulkan data laporan dari hasil K-Means yang SUDAH tersimpan.
 * Service ini TIDAK menghitung ulang K-Means.
 */
class LaporanKerawananService
{
    public const JENIS = [
        'penyelamatan' => 'Penyelamatan',
        'kebakaran'    => 'Kebakaran',
    ];

    public const URUTAN_TINGKAT = [
        'Tinggi' => 0,
        'Sedang' => 1,
        'Rendah' => 2,
    ];


    /*
    |--------------------------------------------------------------------------
    | Opsi periode
    |--------------------------------------------------------------------------
    | Pasangan 2 tahun berurutan + seluruh rentang (dari data penduduk),
    | ditambah rentang lain yang pernah diproses.
    */

    public function opsiPeriode(): Collection
    {
        $tahun = Penduduk::query()
            ->select('tahun')
            ->distinct()
            ->orderBy('tahun')
            ->pluck('tahun')
            ->map(fn ($t) => (int) $t)
            ->values();

        // value periode => daftar jenis yang sudah dianalisis
        $sudahDianalisis = KMeansRun::query()
            ->whereNotNull('tahun_awal')
            ->get(['jenis_kejadian', 'tahun_awal', 'tahun_akhir'])
            ->groupBy(fn ($r) => $r->tahun_awal . '/' . $r->tahun_akhir)
            ->map(fn ($grup) => $grup->pluck('jenis_kejadian')->unique()->values()->all());

        $label = [];

        for ($i = 0; $i < $tahun->count() - 1; $i++) {

            $awal  = $tahun[$i];
            $akhir = $tahun[$i + 1];

            $label["{$awal}/{$akhir}"] = "{$awal}/{$akhir} ("
                . ($akhir - $awal + 1) . ' tahun)';
        }

        if ($tahun->isNotEmpty() && $tahun->first() !== $tahun->last()) {

            $min = $tahun->first();
            $max = $tahun->last();

            $label["{$min}/{$max}"] = "{$min}/{$max} (semua)";
        }

        foreach ($sudahDianalisis->keys() as $value) {

            if (isset($label[$value])) {
                continue;
            }

            [$awal, $akhir] = array_map('intval', explode('/', $value));

            $label[$value] = $awal === $akhir
                ? (string) $awal
                : "{$awal}/{$akhir} (" . ($akhir - $awal + 1) . ' tahun)';
        }

        return collect($label)
            ->map(fn ($teks, $value) => [
                'value' => $value,
                'label' => $teks,
                'jenis' => $sudahDianalisis->get($value, []),
            ])
            ->values();
    }


    /**
     * Pilih periode. Kalau tidak valid, pakai periode terbaru
     * yang sudah punya hasil (akhir terbesar, lalu rentang terpanjang).
     */
    public function pilihPeriode(Collection $opsi, ?string $diminta): ?string
    {
        if ($diminta && $opsi->contains('value', $diminta)) {
            return $diminta;
        }

        $default = $opsi
            ->filter(fn ($o) => ! empty($o['jenis']))
            ->sortByDesc(function ($o) {

                [$awal, $akhir] = array_map('intval', explode('/', $o['value']));

                return sprintf('%04d%04d', $akhir, $akhir - $awal);
            })
            ->first();

        return $default['value']
            ?? $opsi->last()['value']
            ?? null;
    }


    /*
    |--------------------------------------------------------------------------
    | Susun laporan
    |--------------------------------------------------------------------------
    */

    public function susun(string $periode): array
    {
        [$awal, $akhir] = array_map('intval', explode('/', $periode));

        $jenis = [];

        foreach (self::JENIS as $kode => $nama) {
            $jenis[$kode] = $this->dataJenis($kode, $nama, $awal, $akhir);
        }

        return [
            'periode'      => $periode,
            'label'        => $awal === $akhir ? (string) $awal : "{$awal}-{$akhir}",
            'awal'         => $awal,
            'akhir'        => $akhir,
            'jenis'        => $jenis,
            'ada_data'     => collect($jenis)->contains(fn ($j) => $j['run'] !== null),
            'dicetak_pada' => now(),
            'dicetak_oleh' => auth()->user()?->name ?? '-',
        ];
    }


    protected function dataJenis(
        string $kode,
        string $nama,
        int $awal,
        int $akhir
    ): array {

        $run = KMeansRun::with('user')
            ->where('jenis_kejadian', $kode)
            ->where('tahun_awal', $awal)
            ->where('tahun_akhir', $akhir)
            ->latest('id')
            ->first();

        if (! $run) {

            return [
                'kode'      => $kode,
                'nama'      => $nama,
                'run'       => null,
                'baris'     => collect(),
                'tinggi'    => collect(),
                'ringkasan' => null,
                'profil'    => [],
            ];
        }

        // Urut: Tinggi -> Sedang -> Rendah, lalu frekuensi terbanyak, lalu nama.
        $baris = $run->results()
            ->with('kelurahan.kecamatan')
            ->get()
            ->sort(function ($a, $b) {

                return [
                    self::URUTAN_TINGKAT[$a->tingkat_kerawanan] ?? 9,
                    -$a->jumlah_kejadian,
                    $a->kelurahan->nama ?? '',
                ] <=> [
                    self::URUTAN_TINGKAT[$b->tingkat_kerawanan] ?? 9,
                    -$b->jumlah_kejadian,
                    $b->kelurahan->nama ?? '',
                ];
            })
            ->values();

        $tinggi = $baris->where('tingkat_kerawanan', 'Tinggi')->values();

        $ringkasan = [
            'jumlah_kelurahan' => $baris->count(),
            'total_kejadian'   => (int) $baris->sum('jumlah_kejadian'),
            'tinggi'           => $tinggi->count(),
            'sedang'           => $baris->where('tingkat_kerawanan', 'Sedang')->count(),
            'rendah'           => $baris->where('tingkat_kerawanan', 'Rendah')->count(),
        ];

        $profil = collect(array_keys(self::URUTAN_TINGKAT))
            ->map(function ($tingkat) use ($baris) {

                $grup = $baris->where('tingkat_kerawanan', $tingkat);

                return [
                    'tingkat'   => $tingkat,
                    'jumlah'    => $grup->count(),
                    'frekuensi' => $grup->avg('jumlah_kejadian') ?? 0,
                    'kepadatan' => $grup->avg(
                        fn ($r) => (float) $r->kepadatan_penduduk
                    ) ?? 0,
                ];
            })
            ->all();

        return [
            'kode'      => $kode,
            'nama'      => $nama,
            'run'       => $run,
            'baris'     => $baris,
            'tinggi'    => $tinggi,
            'ringkasan' => $ringkasan,
            'profil'    => $profil,
        ];
    }
}