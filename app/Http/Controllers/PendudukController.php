<?php

namespace App\Http\Controllers;

use App\Exports\PendudukTemplateExport;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PendudukImport;

class PendudukController extends Controller
{
    /**
     * Menampilkan data penduduk.
     */
    public function index(Request $request)
    {
        $tahun = $request->tahun;

        $query = Penduduk::with('kelurahan.kecamatan')
            ->orderBy('tahun', 'desc')
            ->orderBy('kelurahan_id');

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $penduduks = $query
            ->paginate(20)
            ->withQueryString();

        $tahunList = Penduduk::query()
            ->select('tahun')
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $title = 'Data Penduduk';

        $tahunTemplate = range(2022, 2025);

        return view('admin.penduduk.index', compact(
            'penduduks', 'tahunList', 'tahun', 'title', 'tahunTemplate'
        ));
    }

    /**
     * Download template Excel.
     */
    public function downloadTemplate(Request $request)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'between:2000,2100'],
        ]);

        $tahun = (int) $validated['tahun'];

        return Excel::download(
            new PendudukTemplateExport($tahun),
            "template-data-penduduk-{$tahun}.xlsx"
        );
    }
    public function import(Request $request)
    {
        $request->validate([
            'tahun' => ['required', 'integer', 'between:2000,2100'],
            'file'  => ['required', 'file', 'mimes:xlsx,xls', 'max:5120'],
        ]);

        $import = new PendudukImport((int) $request->tahun);

        Excel::import($import, $request->file('file'));

        if (! empty($import->errors)) {
            return back()
                ->with('import_errors', $import->errors)
                ->withInput();
        }

        return redirect()
            ->route('penduduk.index', ['tahun' => $request->tahun])
            ->with('success', "Berhasil mengimpor {$import->imported} data penduduk tahun {$request->tahun}.");
    }
}