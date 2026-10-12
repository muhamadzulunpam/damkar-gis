<?php

namespace App\Http\Controllers;

use App\Models\Regu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;

class ReguController extends Controller
{
    public function index(Request $request)
    {
        $query = Regu::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere(
                        'keterangan',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        if ($request->filled('aktif')) {
            $query->where('aktif', $request->aktif);
        }

        $regus = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $title = 'Daftar Regu';
        return view('admin.regu.index', compact('regus', 'title'));
    }

    public function create()
    {
        $title = 'Tambah Regu';
        return view('admin.regu.create', compact('title'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
                'unique:regus,nama',
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

        $regu = Regu::create($validated);

        ActivityLogger::log(
            'Create',
            'Menambahkan Regu "' . $regu->nama . '".',
            'Regu',
            'create'
        );

        return redirect()
            ->route('regus.index')
            ->with(
                'success',
                'Regu berhasil ditambahkan.'
            );
    }

    public function show(Regu $regu)
    {
        $regu->loadCount([
            'kejadianKebakarans',
            'kejadianPenyelamatans',
        ]);
        $title = 'Detail Regu';

        return view('admin.regu.show', compact('regu', 'title'));
    }

    public function edit(Regu $regu)
    {
        $title = 'Edit Regu';
        return view('admin.regu.edit', compact('regu', 'title'));
    }

    public function update(Request $request, Regu $regu)
    {
        $validated = $request->validate([
            'nama' => [
                'required', 
                'string',
                'max:100',
                Rule::unique('regus', 'nama')
                    ->ignore($regu->id),
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

        $regu->update($validated);

        ActivityLogger::log(
            'Update',
            'Memperbarui Regu "' . $regu->nama . '".',
            'Regu',
            'update'
        );

        return redirect()
            ->route('regus.index')
            ->with(
                'success',
                'Regu berhasil diperbarui.'
            );
    }

    public function destroy(Regu $regu)
    {
        $regu->delete();

        ActivityLogger::log(
            'Delete',
            'Menghapus Regu "' . $regu->nama . '".',
            'Regu',
            'delete'
        );
        return redirect()
            ->route('regus.index')
            ->with(
                'success',
                'Regu berhasil dihapus.'
            );
    }
}