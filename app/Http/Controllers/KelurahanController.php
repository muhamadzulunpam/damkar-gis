<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;

class KelurahanController extends Controller
{
    /**
     * Menampilkan daftar kelurahan.
     */
    public function index(Request $request)
    {
        $query = Kelurahan::with('kecamatan');

        // Search
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('keterangan', 'like', '%' . $search . '%');

            });
        }

        // Filter Kecamatan
        if ($request->filled('kecamatan_id')) {

            $query->where(
                'kecamatan_id',
                $request->kecamatan_id
            );
        }

        $kelurahans = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $kecamatans = Kecamatan::orderBy('nama')->get();

        return view('admin.kelurahans.index', [
            'title' => 'Kelurahan',
            'kelurahans' => $kelurahans,
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Form tambah.
     */
    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama')->get();

        return view('admin.kelurahans.create', [
            'title' => 'Tambah Kelurahan',
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Simpan kelurahan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kecamatan_id' => [
                'required',
                'exists:kecamatans,id',
            ],

            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kelurahans', 'nama')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'kecamatan_id',
                            $request->kecamatan_id
                        );
                    }),
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $kelurahan = Kelurahan::create($validated);

        ActivityLogger::log(
            'Create',
            'Menambahkan Kelurahan "' . $kelurahan->nama . '".',
            'Kelurahan',
            'create'
        );

        return redirect()
            ->route('kelurahans.index')
            ->with(
                'success',
                'Kelurahan berhasil ditambahkan.'
            );
    }

    /**
     * Detail.
     */
    public function show(Kelurahan $kelurahan)
    {
        $kelurahan->load('kecamatan');

        return view('admin.kelurahans.show', [
            'title' => 'Detail Kelurahan',
            'kelurahan' => $kelurahan,
        ]);
    }

    /**
     * Form edit.
     */
    public function edit(Kelurahan $kelurahan)
    {
        $kecamatans = Kecamatan::orderBy('nama')->get();

        return view('admin.kelurahans.edit', [
            'title' => 'Edit Kelurahan',
            'kelurahan' => $kelurahan,
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Update.
     */
    public function update(
        Request $request,
        Kelurahan $kelurahan
    ) {
        $validated = $request->validate([
            'kecamatan_id' => [
                'required',
                'exists:kecamatans,id',
            ],

            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kelurahans', 'nama')
                    ->where(function ($query) use ($request) {
                        return $query->where(
                            'kecamatan_id',
                            $request->kecamatan_id
                        );
                    })
                    ->ignore($kelurahan->id),
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $kelurahan->update($validated);

        ActivityLogger::log(
            'Update',
            'Memperbarui Kelurahan "' . $kelurahan->nama . '".',
            'Kelurahan',
            'update'
        );

        return redirect()
            ->route('kelurahans.index')
            ->with(
                'success',
                'Kelurahan berhasil diperbarui.'
            );
    }

    /**
     * Hapus.
     */
    public function destroy(Kelurahan $kelurahan)
    {
        $kelurahan->delete();
        ActivityLogger::log(
            'Delete',
            'Menghapus Kelurahan "' . $kelurahan->nama . '".',
            'Kelurahan',
            'delete'
        );

        return redirect()
            ->route('kelurahans.index')
            ->with(
                'success',
                'Kelurahan berhasil dihapus.'
            );
    }
}