<?php

namespace App\Http\Controllers;

use App\Exports\Laporan\LaporanKerawananExport;
use App\Helpers\ActivityLogger;
use App\Services\LaporanKerawananService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function __construct(
        protected LaporanKerawananService $service
    ) {
    }


    /**
     * Halaman laporan (pratinjau + tombol unduh).
     */
    public function index(Request $request)
    {
        $opsi = $this->service->opsiPeriode();

        $periode = $this->service->pilihPeriode(
            $opsi,
            $request->input('periode')
        );

        $laporan = $periode
            ? $this->service->susun($periode)
            : null;

        $title = 'Laporan';

        return view('admin.laporan.index', compact(
            'opsi',
            'periode',
            'laporan',
            'title'
        ));
    }


    /**
     * Unduh laporan PDF.
     */
    public function pdf(Request $request)
    {
        $laporan = $this->siapkan($request);

        if (! $laporan) {
            return $this->gagal();
        }

        $this->catat('PDF', $laporan);

        $pdf = Pdf::loadView('admin.laporan.pdf', [
                'laporan' => $laporan,
            ])
            ->setPaper('a4', 'portrait');

        return $pdf->download(
            "laporan-kerawanan-{$laporan['label']}.pdf"
        );
    }


    /**
     * Unduh laporan Excel.
     */
    public function excel(Request $request)
    {
        $laporan = $this->siapkan($request);

        if (! $laporan) {
            return $this->gagal();
        }

        $this->catat('Excel', $laporan);

        return Excel::download(
            new LaporanKerawananExport($laporan),
            "laporan-kerawanan-{$laporan['label']}.xlsx"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    /**
     * Ambil data laporan. Null kalau periode tidak valid
     * atau belum ada hasil K-Means sama sekali.
     */
    protected function siapkan(Request $request): ?array
    {
        $opsi = $this->service->opsiPeriode();

        $periode = $this->service->pilihPeriode(
            $opsi,
            $request->input('periode')
        );

        if (! $periode) {
            return null;
        }

        $laporan = $this->service->susun($periode);

        return $laporan['ada_data'] ? $laporan : null;
    }

    protected function gagal()
    {
        return redirect()
            ->route('laporan.index')
            ->with(
                'error',
                'Belum ada hasil K-Means untuk periode tersebut. '
                . 'Jalankan analisis K-Means terlebih dahulu.'
            );
    }

    /**
     * Catat aktivitas unduh. Kalau pencatatan gagal,
     * unduhan tetap berjalan.
     */
    protected function catat(string $format, array $laporan): void
    {
        rescue(fn () => ActivityLogger::log(
            'Unduh Laporan',
            "Mengunduh laporan kerawanan periode {$laporan['label']} ({$format})",
            'Laporan',
            'download'
        ));
    }
}