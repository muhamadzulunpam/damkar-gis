<?php

namespace App\Http\Controllers;

use App\Models\JenisKejadian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisKejadianController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisKejadian::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('keterangan', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('aktif')) {
            $query->where('aktif', $request->aktif);
        }

        $jenisKejadians = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.jenis-kejadians.index', [
            'title' => 'Jenis Kejadian',
            'jenisKejadians' => $jenisKejadians,
        ]);
    }

    public function create()
    {
        return view('admin.jenis-kejadians.create', [
            'title' => 'Tambah Jenis Kejadian',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                'unique:jenis_kejadian,nama',
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],

            'aktif' => [
                'required',
                'boolean',
            ],
        ]);

        JenisKejadian::create($validated);

        return redirect()
            ->route('jenis-kejadians.index')
            ->with(
                'success',
                'Jenis kejadian berhasil ditambahkan.'
            );
    }

    public function show(JenisKejadian $jenisKejadian)
    {
        $jenisKejadian->load('kategoris');

        return view('admin.jenis-kejadians.show', [
            'title' => 'Detail Jenis Kejadian',
            'jenisKejadian' => $jenisKejadian,
        ]);
    }

    public function edit(JenisKejadian $jenisKejadian)
    {
        return view('admin.jenis-kejadians.edit', [
            'title' => 'Edit Jenis Kejadian',
            'jenisKejadian' => $jenisKejadian,
        ]);
    }

    public function update(
        Request $request,
        JenisKejadian $jenisKejadian
    ) {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jenis_kejadian', 'nama')
                    ->ignore($jenisKejadian->id),
            ],

            'keterangan' => [
                'nullable',
                'string',
            ],

            'aktif' => [
                'required',
                'boolean',
            ],
        ]);

        $jenisKejadian->update($validated);

        return redirect()
            ->route('jenis-kejadians.index')
            ->with(
                'success',
                'Jenis kejadian berhasil diperbarui.'
            );
    }

    public function destroy(JenisKejadian $jenisKejadian)
    {
        // Jangan hapus jika sudah mempunyai kategori.
        if ($jenisKejadian->kategoris()->exists()) {
            return redirect()
                ->route('jenis-kejadians.index')
                ->with(
                    'error',
                    'Jenis kejadian tidak dapat dihapus karena masih memiliki kategori.'
                );
        }

        $jenisKejadian->delete();

        return redirect()
            ->route('jenis-kejadians.index')
            ->with(
                'success',
                'Jenis kejadian berhasil dihapus.'
            );
    }
}
