<?php

namespace App\Http\Controllers;

use App\Models\JenisKejadian;
use App\Models\KategoriKejadian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriKejadianController extends Controller
{
    public function index(Request $request)
    {
        $query = KategoriKejadian::with(
            'jenisKejadian'
        );

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'nama',
                    'like',
                    '%' . $search . '%'
                );

                $q->orWhere(
                    'keterangan',
                    'like',
                    '%' . $search . '%'
                );

            });
        }

        if ($request->filled('jenis_kejadian_id')) {

            $query->where(
                'jenis_kejadian_id',
                $request->jenis_kejadian_id
            );
        }

        if ($request->filled('aktif')) {

            $query->where(
                'aktif',
                $request->aktif
            );
        }

        $kategoris = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $jenisKejadians = JenisKejadian::where(
            'aktif',
            true
        )
            ->orderBy('nama')
            ->get();

        return view(
            'admin.kategori-kejadians.index',
            [
                'title' => 'Kategori Kejadian',
                'kategoris' => $kategoris,
                'jenisKejadians' => $jenisKejadians,
            ]
        );
    }

    public function create()
    {
        $jenisKejadians = JenisKejadian::where(
            'aktif',
            true
        )
            ->orderBy('nama')
            ->get();

        return view(
            'admin.kategori-kejadians.create',
            [
                'title' => 'Tambah Kategori Kejadian',
                'jenisKejadians' => $jenisKejadians,
            ]
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'jenis_kejadian_id' => [
                'required',
                'exists:jenis_kejadian,id',
            ],

            'nama' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'kategori_kejadian',
                    'nama'
                )->where(function ($query) use ($request) {

                    return $query->where(
                        'jenis_kejadian_id',
                        $request->jenis_kejadian_id
                    );

                }),
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

        KategoriKejadian::create(
            $validated
        );

        return redirect()
            ->route('kategori-kejadians.index')
            ->with(
                'success',
                'Kategori kejadian berhasil ditambahkan.'
            );
    }

    public function show(
        KategoriKejadian $kategoriKejadian
    ) {
        $kategoriKejadian->load(
            'jenisKejadian'
        );

        return view(
            'admin.kategori-kejadians.show',
            [
                'title' => 'Detail Kategori Kejadian',
                'kategoriKejadian' => $kategoriKejadian,
            ]
        );
    }

    public function edit(
        KategoriKejadian $kategoriKejadian
    ) {
        $jenisKejadians = JenisKejadian::where(
            'aktif',
            true
        )
            ->orderBy('nama')
            ->get();

        return view(
            'admin.kategori-kejadians.edit',
            [
                'title' => 'Edit Kategori Kejadian',
                'kategoriKejadian' => $kategoriKejadian,
                'jenisKejadians' => $jenisKejadians,
            ]
        );
    }

    public function update(
        Request $request,
        KategoriKejadian $kategoriKejadian
    ) {
        $validated = $request->validate([

            'jenis_kejadian_id' => [
                'required',
                'exists:jenis_kejadian,id',
            ],

            'nama' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'kategori_kejadian',
                    'nama'
                )
                    ->where(function ($query) use ($request) {

                        return $query->where(
                            'jenis_kejadian_id',
                            $request->jenis_kejadian_id
                        );

                    })
                    ->ignore(
                        $kategoriKejadian->id
                    ),
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

        $kategoriKejadian->update(
            $validated
        );

        return redirect()
            ->route('kategori-kejadians.index')
            ->with(
                'success',
                'Kategori kejadian berhasil diperbarui.'
            );
    }

    public function destroy(
        KategoriKejadian $kategoriKejadian
    ) {
        $kategoriKejadian->delete();

        return redirect()
            ->route('kategori-kejadians.index')
            ->with(
                'success',
                'Kategori kejadian berhasil dihapus.'
            );
    }
}