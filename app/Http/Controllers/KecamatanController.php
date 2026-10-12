<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;

class KecamatanController extends Controller
{
    /**
     * Menampilkan daftar kecamatan.
     */
    public function index(Request $request)
    {
        $query = Kecamatan::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        $kecamatans = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.kecamatans.index', [
            'title' => 'Kecamatan',
            'kecamatans' => $kecamatans,
        ]);
    }

    /**
     * Form tambah kecamatan.
     */
    public function create()
    {
        return view('admin.kecamatans.create', [
            'title' => 'Tambah Kecamatan',
        ]);
    }

    /**
     * Simpan kecamatan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                'unique:kecamatans,nama',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $kecamatan = Kecamatan::create($validated);

        ActivityLogger::log(
            'Create',
            'Menambahkan Kecamatan "' . $kecamatan->nama . '".',
            'Kecamatan',
            'create'
        );

        return redirect()
            ->route('kecamatans.index')
            ->with('success', 'Kecamatan berhasil ditambahkan.');
    }

    /**
     * Detail kecamatan.
     */
    public function show(Kecamatan $kecamatan)
    {
        return view('admin.kecamatans.show', [
            'title' => 'Detail Kecamatan',
            'kecamatan' => $kecamatan,
        ]);
    }

    /**
     * Form edit kecamatan.
     */
    public function edit(Kecamatan $kecamatan)
    {
        return view('admin.kecamatans.edit', [
            'title' => 'Edit Kecamatan',
            'kecamatan' => $kecamatan,
        ]);
    } 

    /**
     * Update kecamatan.
     */
    public function update(Request $request, Kecamatan $kecamatan)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kecamatans', 'nama')
                    ->ignore($kecamatan->id),
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);

        $kecamatan->update($validated);

        ActivityLogger::log(
            'Update',
            'Memperbarui Kecamatan "' . $kecamatan->nama . '".',
            'Kecamatan',
            'update'
        );

        return redirect()
            ->route('kecamatans.index')
            ->with('success', 'Kecamatan berhasil diperbarui.');
    }

    /**
     * Hapus kecamatan.
     */
    public function destroy(Kecamatan $kecamatan)
    {
        $kecamatan->delete();

        ActivityLogger::log(
            'Delete',
            'Menghapus Kecamatan "' . $kecamatan->nama . '".',
            'Kecamatan',
            'delete'
        );

        return redirect()
            ->route('kecamatans.index')
            ->with('success', 'Kecamatan berhasil dihapus.');
    }
}