<?php

namespace App\Services;

use App\Models\Kelurahan;
use App\Models\KejadianKebakaran;
use App\Models\KejadianPenyelamatan;
use App\Models\KMeansResult;
use App\Models\KMeansRun;
use App\Models\Penduduk;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Random\Engine\Mt19937;
use Random\Randomizer;

class KMeansKerawananService
{
    // Konstanta sama dengan notebook
    public const K = 3;
    public const N_INIT = 50;
    public const RANDOM_STATE = 42;

    protected int $maxIter = 300;
    protected float $tol = 1e-10; // pada jarak kuadrat

    public function proses(
        string $jenis,
        int $tahunAwal,
        int $tahunAkhir,
        int $userId
    ): KMeansRun {

        // 1. Validasi
        if (! in_array($jenis, ['penyelamatan', 'kebakaran'], true)) {
            throw new InvalidArgumentException("Jenis harus 'penyelamatan' atau 'kebakaran'.");
        }
        if ($tahunAwal > $tahunAkhir) {
            throw new InvalidArgumentException('Tahun awal tidak boleh lebih besar dari tahun akhir.');
        }

        // 2. Master: mulai dari SELURUH kelurahan
        $kelurahans = Kelurahan::with('kecamatan')->orderBy('id')->get();

        // 3. Kejadian pada rentang tahun
        $query = ($jenis === 'kebakaran' ? KejadianKebakaran::query() : KejadianPenyelamatan::query())
            ->whereBetween('tanggal', ["{$tahunAwal}-01-01 00:00:00", "{$tahunAkhir}-12-31 23:59:59"]);

        $frekuensi = (clone $query)
            ->selectRaw('kelurahan_id, COUNT(*) as total')
            ->groupBy('kelurahan_id')
            ->pluck('total', 'kelurahan_id');

        if ($frekuensi->sum() === 0) {
            throw new InvalidArgumentException('Tidak ada kejadian pada rentang tahun tersebut.');
        }

        // Informasi pendukung (BUKAN input K-Means)
        $kerugianSql = $jenis === 'kebakaran'
            ? 'SUM(COALESCE(kerugian_harta,0) + COALESCE(kerugian_benda,0))'
            : 'SUM(COALESCE(kerugian_harta,0))';

        $info = (clone $query)
            ->selectRaw("kelurahan_id, SUM(COALESCE(korban_jiwa,0)) as korban, {$kerugianSql} as kerugian")
            ->groupBy('kelurahan_id')
            ->get()
            ->keyBy('kelurahan_id');

        // 4. Kepadatan penduduk
        $pendudukAll = Penduduk::query()->get()->groupBy('kelurahan_id');

        $dataset = [];
        $tanpaPenduduk = [];

        foreach ($kelurahans as $kel) {
            $kepadatan = $this->kepadatan(
                $pendudukAll->get($kel->id),
                $tahunAwal,
                $tahunAkhir
            );

            if ($kepadatan === null) {
                $tanpaPenduduk[] = $kel->nama;
                continue;
            }

            $dataset[] = [
                'kelurahan_id'       => $kel->id,
                'frekuensi_kejadian' => (int) ($frekuensi[$kel->id] ?? 0),
                'kepadatan_penduduk' => $kepadatan,
                'korban'             => (int) ($info[$kel->id]->korban ?? 0),
                'kerugian'           => (float) ($info[$kel->id]->kerugian ?? 0),
            ];
        }

        if (! empty($tanpaPenduduk)) {
            $contoh = implode(', ', array_slice($tanpaPenduduk, 0, 5));
            $sisa = count($tanpaPenduduk) - 5;
            throw new InvalidArgumentException(
                "Data penduduk/luas wilayah belum lengkap untuk {$contoh}"
                . ($sisa > 0 ? " dan {$sisa} kelurahan lain." : '.')
                . ' Lengkapi di menu Data Penduduk.'
            );
        }

        // 5. Normalisasi Min-Max (dihitung ulang tiap analisis)
        $X = $this->minMax($dataset, ['frekuensi_kejadian', 'kepadatan_penduduk']);

        $unik = count(array_unique(array_map(fn ($r) => implode('|', $r), $X)));
        if ($unik < self::K) {
            throw new InvalidArgumentException('Data terlalu seragam untuk dibagi menjadi ' . self::K . ' klaster.');
        }

        // 6. K-Means k = 3
        $fit = $this->kmeans($X, self::K);

        // 7. Kualitas hasil
        [$silhouette, $silhouetteSample] = $this->silhouette($X, $fit['labels'], self::K);
        $daviesBouldin = $this->daviesBouldin($X, $fit['labels'], self::K);

        // Evaluasi k = 2..8
        $evaluasi = [];
        for ($k = 2; $k <= 8; $k++) {
            if ($unik < $k) {
                continue;
            }
            $f = $this->kmeans($X, $k);
            [$s] = $this->silhouette($X, $f['labels'], $k);
            $evaluasi[] = [
                'k'              => $k,
                'inertia'        => round($f['inertia'], 3),
                'silhouette'     => round($s, 3),
                'davies_bouldin' => round($this->daviesBouldin($X, $f['labels'], $k), 3),
            ];
        }

        // 8. Label dari skor gabungan pusat klaster (kecil -> besar)
        $skor = array_map(fn ($c) => array_sum($c) / count($c), $fit['centers']);
        asort($skor);
        $urutan = array_keys($skor);

        $mapping = [
            $urutan[0] => ['Rendah', '#22c55e'],
            $urutan[1] => ['Sedang', '#eab308'],
            $urutan[2] => ['Tinggi', '#ef4444'],
        ];

        // 9. Simpan
        return DB::transaction(function () use (
            $jenis, $tahunAwal, $tahunAkhir, $userId,
            $dataset, $fit, $mapping, $silhouette, $silhouetteSample, $daviesBouldin, $evaluasi
        ) {
            $run = KMeansRun::create([
                'jenis_kejadian' => $jenis,
                'tahun'          => $tahunAkhir,
                'tahun_awal'     => $tahunAwal,
                'tahun_akhir'    => $tahunAkhir,
                'jumlah_cluster' => self::K,
                'diproses_oleh'  => $userId,
                'silhouette'     => round($silhouette, 3),
                'davies_bouldin' => round($daviesBouldin, 3),
                'evaluasi_k'     => $evaluasi,
            ]);

            foreach ($dataset as $i => $d) {
                $cluster = $fit['labels'][$i];

                KMeansResult::create([
                    'kmeans_run_id'        => $run->id,
                    'kelurahan_id'         => $d['kelurahan_id'],
                    'jumlah_kejadian'      => $d['frekuensi_kejadian'],
                    'jumlah_korban'        => $d['korban'],
                    'total_kerugian'       => $d['kerugian'],
                    'kepadatan_penduduk'   => round($d['kepadatan_penduduk'], 2),
                    'silhouette_kelurahan' => round($silhouetteSample[$i], 3),
                    'cluster'              => $cluster,
                    'tingkat_kerawanan'    => $mapping[$cluster][0],
                    'warna'                => $mapping[$cluster][1],
                ]);
            }

            return $run->load(['user', 'results.kelurahan.kecamatan']);
        });
    }

    /**
     * Rata-rata penduduk pada rentang tahun / luas wilayah.
     * Tahun yang tidak ada memakai tahun terdekat (seperti notebook).
     */
    protected function kepadatan($records, int $awal, int $akhir): ?float
    {
        if (! $records || $records->isEmpty()) {
            return null;
        }

        $byTahun = $records->keyBy('tahun');
        $tahunAda = $byTahun->keys()->map(fn ($t) => (int) $t)->sort()->values()->all();

        $penduduk = [];
        $luas = [];

        for ($t = $awal; $t <= $akhir; $t++) {
            $pakai = $tahunAda[0];
            foreach ($tahunAda as $th) {
                if (abs($th - $t) < abs($pakai - $t)) {
                    $pakai = $th;
                }
            }
            $penduduk[] = (float) $byTahun[$pakai]->jumlah_penduduk;
            $luas[] = (float) $byTahun[$pakai]->luas_wilayah_km2;
        }

        $rataLuas = array_sum($luas) / count($luas);

        return $rataLuas > 0
            ? (array_sum($penduduk) / count($penduduk)) / $rataLuas
            : null;
    }

    /** Min-Max 0..1 per kolom. */
    protected function minMax(array $dataset, array $kolom): array
    {
        $min = $max = [];
        foreach ($kolom as $c) {
            $nilai = array_column($dataset, $c);
            $min[$c] = min($nilai);
            $max[$c] = max($nilai);
        }

        $X = [];
        foreach ($dataset as $row) {
            $baris = [];
            foreach ($kolom as $c) {
                $rentang = $max[$c] - $min[$c];
                $baris[] = $rentang == 0 ? 0.0 : ($row[$c] - $min[$c]) / $rentang;
            }
            $X[] = $baris;
        }

        return $X;
    }

    /**
     * K-Means++ diulang N_INIT kali, diambil inertia terkecil.
     */
    protected function kmeans(array $X, int $k): array
    {
        $rng = new Randomizer(new Mt19937(self::RANDOM_STATE));
        $best = null;

        for ($i = 0; $i < self::N_INIT; $i++) {
            $hasil = $this->lloyd($X, $this->initPlusPlus($X, $k, $rng));

            if ($best === null || $hasil['inertia'] < $best['inertia'] - 1e-12) {
                $best = $hasil;
            }
        }

        return $best;
    }

    protected function initPlusPlus(array $X, int $k, Randomizer $rng): array
    {
        $n = count($X);
        $centers = [$X[$rng->getInt(0, $n - 1)]];
        $d2 = array_fill(0, $n, INF);

        while (count($centers) < $k) {
            $last = end($centers);

            foreach ($X as $i => $p) {
                $d = $this->sqDist($p, $last);
                if ($d < $d2[$i]) {
                    $d2[$i] = $d;
                }
            }

            $total = array_sum($d2);

            if ($total <= 0) {
                $idx = $rng->getInt(0, $n - 1);
            } else {
                $target = ($rng->getInt(0, PHP_INT_MAX) / PHP_INT_MAX) * $total;
                $acc = 0.0;
                $idx = $n - 1;
                foreach ($d2 as $i => $d) {
                    $acc += $d;
                    if ($acc >= $target) {
                        $idx = $i;
                        break;
                    }
                }
            }

            $centers[] = $X[$idx];
        }

        return $centers;
    }

    protected function lloyd(array $X, array $centers): array
    {
        for ($it = 0; $it < $this->maxIter; $it++) {
            [$labels] = $this->assign($X, $centers);
            $baru = $this->updateCenters($X, $labels, $centers);

            $shift = 0.0;
            foreach ($centers as $c => $center) {
                $shift = max($shift, $this->sqDist($center, $baru[$c]));
            }

            $centers = $baru;

            if ($shift <= $this->tol) {
                break;
            }
        }

        [$labels, $inertia] = $this->assign($X, $centers);

        return ['labels' => $labels, 'centers' => $centers, 'inertia' => $inertia];
    }

    protected function assign(array $X, array $centers): array
    {
        $labels = [];
        $inertia = 0.0;

        foreach ($X as $i => $p) {
            $best = 0;
            $bd = INF;
            foreach ($centers as $c => $center) {
                $d = $this->sqDist($p, $center);
                if ($d < $bd) {
                    $bd = $d;
                    $best = $c;
                }
            }
            $labels[$i] = $best;
            $inertia += $bd;
        }

        return [$labels, $inertia];
    }

    protected function updateCenters(array $X, array $labels, array $lama): array
    {
        $k = count($lama);
        $dim = count($X[0]);
        $sum = array_fill(0, $k, array_fill(0, $dim, 0.0));
        $cnt = array_fill(0, $k, 0);

        foreach ($X as $i => $p) {
            $cnt[$labels[$i]]++;
            foreach ($p as $d => $v) {
                $sum[$labels[$i]][$d] += $v;
            }
        }

        $baru = [];
        foreach ($sum as $c => $s) {
            if ($cnt[$c] === 0) {
                // Klaster kosong: pakai titik terjauh dari pusatnya
                $far = 0;
                $fd = -1.0;
                foreach ($X as $i => $p) {
                    $d = $this->sqDist($p, $lama[$labels[$i]]);
                    if ($d > $fd) {
                        $fd = $d;
                        $far = $i;
                    }
                }
                $baru[$c] = $X[$far];
                continue;
            }
            $baru[$c] = array_map(fn ($v) => $v / $cnt[$c], $s);
        }

        return $baru;
    }

    /** Silhouette rata-rata dan per sampel. */
    protected function silhouette(array $X, array $labels, int $k): array
    {
        $n = count($X);
        $scores = [];

        for ($i = 0; $i < $n; $i++) {
            $sum = array_fill(0, $k, 0.0);
            $cnt = array_fill(0, $k, 0);

            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    continue;
                }
                $sum[$labels[$j]] += sqrt($this->sqDist($X[$i], $X[$j]));
                $cnt[$labels[$j]]++;
            }

            $own = $labels[$i];

            if ($cnt[$own] === 0) {
                $scores[$i] = 0.0;
                continue;
            }

            $a = $sum[$own] / $cnt[$own];
            $b = INF;
            for ($c = 0; $c < $k; $c++) {
                if ($c !== $own && $cnt[$c] > 0) {
                    $b = min($b, $sum[$c] / $cnt[$c]);
                }
            }

            $m = max($a, $b);
            $scores[$i] = (is_infinite($b) || $m == 0) ? 0.0 : ($b - $a) / $m;
        }

        return [array_sum($scores) / $n, $scores];
    }

    protected function daviesBouldin(array $X, array $labels, int $k): float
    {
        $centers = $this->updateCenters($X, $labels, array_fill(0, $k, $X[0]));
        $s = array_fill(0, $k, 0.0);
        $cnt = array_fill(0, $k, 0);

        foreach ($X as $i => $p) {
            $s[$labels[$i]] += sqrt($this->sqDist($p, $centers[$labels[$i]]));
            $cnt[$labels[$i]]++;
        }
        foreach ($s as $c => $v) {
            $s[$c] = $cnt[$c] ? $v / $cnt[$c] : 0.0;
        }

        $total = 0.0;
        for ($i = 0; $i < $k; $i++) {
            $maks = 0.0;
            for ($j = 0; $j < $k; $j++) {
                if ($i === $j) {
                    continue;
                }
                $m = sqrt($this->sqDist($centers[$i], $centers[$j]));
                if ($m > 1e-10) {
                    $maks = max($maks, ($s[$i] + $s[$j]) / $m);
                }
            }
            $total += $maks;
        }

        return $total / $k;
    }

    protected function sqDist(array $a, array $b): float
    {
        $d = 0.0;
        foreach ($a as $i => $v) {
            $d += ($v - $b[$i]) ** 2;
        }

        return $d;
    }
}