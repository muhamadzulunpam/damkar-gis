<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\KejadianKebakaran;
use App\Models\KejadianPenyelamatan;
use App\Models\Kelurahan;
use App\Models\Regu;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==============================
        // STATISTIK UTAMA
        // ==============================

        $totalKebakaran    = KejadianKebakaran::count();
        $totalPenyelamatan = KejadianPenyelamatan::count();
        $totalKejadian     = $totalKebakaran + $totalPenyelamatan;

        $totalKorbanKebakaran    = KejadianKebakaran::sum('korban_jiwa') ?? 0;
        $totalKorbanPenyelamatan = KejadianPenyelamatan::sum('korban_jiwa') ?? 0;
        $totalKorban             = $totalKorbanKebakaran + $totalKorbanPenyelamatan;

        $kecamatanKebakaran = KejadianKebakaran::whereNotNull('kecamatan_id')
            ->distinct('kecamatan_id')
            ->pluck('kecamatan_id');

        $kecamatanPenyelamatan = KejadianPenyelamatan::whereNotNull('kecamatan_id')
            ->distinct('kecamatan_id')
            ->pluck('kecamatan_id');

        $kecamatanTerdampak = $kecamatanKebakaran
            ->merge($kecamatanPenyelamatan)
            ->unique()
            ->count();

        $totalRegu = Regu::where('aktif', true)->count();

        // ==============================
        // DAFTAR PERIODE (tahun_awal/tahun_akhir)
        // ==============================
        //
        // Format: [['value' => '2022/2023', 'label' => '2022/2023 (2 tahun)'], ...]

        $periodeList = $this->susunDaftarPeriode();

        // ==============================
        // PERIODE TERPILIH
        // ==============================

        $periode = $request->input('periode');

        if (! $periodeList->contains('value', $periode)) {
            $periode = $periodeList->first()['value'] ?? null;
        }

        [$tahunAwal, $tahunAkhir] = $this->parsePeriode($periode);

        // ==============================
        // FILTER JENIS
        // ==============================

        $jenis = $request->input('jenis', 'semua');

        if (! in_array($jenis, ['semua', 'kebakaran', 'penyelamatan'], true)) {
            $jenis = 'semua';
        }

        // ==============================
        // GRAFIK: KEJADIAN PER KELURAHAN
        // ==============================

        $kebakaranPerKelurahan = in_array($jenis, ['semua', 'kebakaran'], true)
            ? $this->hitungPerKelurahan(KejadianKebakaran::class, $tahunAwal, $tahunAkhir)
            : collect();

        $penyelamatanPerKelurahan = in_array($jenis, ['semua', 'penyelamatan'], true)
            ? $this->hitungPerKelurahan(KejadianPenyelamatan::class, $tahunAwal, $tahunAkhir)
            : collect();

        $barisKelurahan = Kelurahan::with('kecamatan:id,nama')
            ->orderBy('nama')
            ->get()
            ->map(fn ($kelurahan) => [
                'nama'      => $kelurahan->nama,
                'kecamatan' => $kelurahan->kecamatan?->nama ?? '-',
                'total'     =>
                    (int) ($kebakaranPerKelurahan[$kelurahan->id] ?? 0)
                    + (int) ($penyelamatanPerKelurahan[$kelurahan->id] ?? 0),
            ])
            ->sortByDesc('total')
            ->values();

        $namaJenis = [
            'semua'        => 'Kebakaran dan Penyelamatan',
            'kebakaran'    => 'Kebakaran',
            'penyelamatan' => 'Penyelamatan',
        ];

        $labelPeriode = $this->labelPeriode($tahunAwal, $tahunAkhir);

        $judulGrafik = "Jumlah Kejadian {$namaJenis[$jenis]} per Kelurahan Periode {$labelPeriode}";

        // ==============================
        // KEJADIAN TERBARU (GABUNGAN)
        // ==============================

        $kejadianKebakaranTerbaru = KejadianKebakaran::with(['kecamatan', 'kelurahan', 'regu'])
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->tipe = 'Kebakaran';
                return $item;
            });

        $kejadianPenyelamatanTerbaru = KejadianPenyelamatan::with(['kecamatan', 'kelurahan', 'regu'])
            ->orderByDesc('tanggal')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->tipe = 'Penyelamatan';
                return $item;
            });

        $kejadianTerbaru = $kejadianKebakaranTerbaru
            ->merge($kejadianPenyelamatanTerbaru)
            ->sortByDesc(function ($item) {
                return $item->tanggal . ' ' . ($item->created_at ?? '');
            })
            ->take(5)
            ->values();

        // ==============================
        // KEJADIAN PER KECAMATAN (GABUNGAN, IKUT PERIODE)
        // ==============================

        $kejadianPerKecamatan = Kecamatan::all()->map(function ($kecamatan) use ($tahunAwal, $tahunAkhir) {
            $kebakaran = KejadianKebakaran::where('kecamatan_id', $kecamatan->id)
                ->whereBetween('tanggal', [
                    "{$tahunAwal}-01-01",
                    "{$tahunAkhir}-12-31",
                ])
                ->count();

            $penyelamatan = KejadianPenyelamatan::where('kecamatan_id', $kecamatan->id)
                ->whereBetween('tanggal', [
                    "{$tahunAwal}-01-01",
                    "{$tahunAkhir}-12-31",
                ])
                ->count();

            $kecamatan->total_kejadian = $kebakaran + $penyelamatan;
            return $kecamatan;
        })->sortByDesc('total_kejadian')->values();

        // ==============================
        // RETURN VIEW
        // ==============================

        return view('admin.dashboard', [
            'title' => 'Dashboard',

            // Statistik
            'totalKejadian'      => $totalKejadian,
            'totalKebakaran'     => $totalKebakaran,
            'totalPenyelamatan'  => $totalPenyelamatan,
            'totalKorban'        => $totalKorban,
            'kecamatanTerdampak' => $kecamatanTerdampak,
            'totalRegu'          => $totalRegu,

            // Grafik per kelurahan
            'periode'              => $periode,
            'periodeList'          => $periodeList,
            'labelPeriode'         => $labelPeriode,
            'jenis'                => $jenis,
            'judulGrafik'          => $judulGrafik,
            'labelKelurahan'       => $barisKelurahan->pluck('nama')->all(),
            'jumlahPerKelurahan'   => $barisKelurahan->pluck('total')->all(),
            'kecamatanPerKelurahan'=> $barisKelurahan->pluck('kecamatan')->all(),

            // Data
            'kejadianTerbaru'      => $kejadianTerbaru,
            'kejadianPerKecamatan' => $kejadianPerKecamatan,
        ]);
    }

    /**
     * Susun daftar periode dari data kejadian (gabungan kebakaran & penyelamatan).
     * Format output: [['value' => '2022/2023', 'label' => '2022/2023 (2 tahun)'], ...]
     */
    protected function susunDaftarPeriode(): Collection
    {
        // Ambil semua tahun dari kedua tabel
        $tahunKebakaran = KejadianKebakaran::query()
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->pluck('tahun');

        $tahunPenyelamatan = KejadianPenyelamatan::query()
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->pluck('tahun');

        $tahun = $tahunKebakaran
            ->merge($tahunPenyelamatan)
            ->map(fn ($t) => (int) $t)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        if ($tahun->isEmpty()) {
            return collect();
        }

        $opsi = [];

        // Pasangan 2 tahun berurutan
        for ($i = 0; $i < $tahun->count() - 1; $i++) {
            $awal  = $tahun[$i];
            $akhir = $tahun[$i + 1];
            $opsi["{$awal}/{$akhir}"] = "{$awal}/{$akhir} (" . ($akhir - $awal + 1) . ' tahun)';
        }

        // Seluruh rentang
        $min = $tahun->first();
        $max = $tahun->last();

        if ($min !== $max) {
            $opsi["{$min}/{$max}"] = "{$min}/{$max} (" . ($max - $min + 1) . ' tahun)';
        }

        // Kalau cuma 1 tahun
        if ($tahun->count() === 1) {
            $opsi["{$min}/{$min}"] = "{$min} (1 tahun)";
        }

        return collect($opsi)
            ->map(fn ($label, $value) => [
                'value' => $value,
                'label' => $label,
            ])
            ->sortKeysDesc()
            ->values();
    }

    /**
     * Parse "2022/2023" → [2022, 2023]
     */
    protected function parsePeriode(?string $periode): array
    {
        if (! $periode || ! preg_match('/^(\d{4})\/(\d{4})$/', $periode, $m)) {
            $tahun = now()->year;
            return [$tahun, $tahun];
        }

        return [(int) $m[1], (int) $m[2]];
    }

    /**
     * Label periode: "2022/2023 (2 tahun)" atau "2022 (1 tahun)"
     */
    protected function labelPeriode(int $awal, int $akhir): string
    {
        return $awal === $akhir
            ? (string) $awal
            : "{$awal}/{$akhir}";
    }

    /**
     * Jumlah kejadian per kelurahan pada rentang periode.
     * Hasil: [kelurahan_id => total]
     */
    protected function hitungPerKelurahan(string $model, int $tahunAwal, int $tahunAkhir): Collection
    {
        return $model::query()
            ->whereBetween('tanggal', [
                "{$tahunAwal}-01-01",
                "{$tahunAkhir}-12-31",
            ])
            ->selectRaw('kelurahan_id, COUNT(*) as total')
            ->groupBy('kelurahan_id')
            ->pluck('total', 'kelurahan_id');
    }
}