<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisKejadian;
use App\Models\KategoriKejadian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IncidentController extends Controller
{
    /**
     * Menampilkan daftar incident.
     */
    public function index(Request $request)
    {
        $query = Incident::with([
            'kecamatan',
            'kelurahan',
            'jenisKejadian',
            'kategoriKejadian',
            'user'
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('alamat', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('kecamatan', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kelurahan', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('jenisKejadian', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kategoriKejadian', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Filter Kecamatan
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        // Filter Jenis Kejadian
        if ($request->filled('jenis_kejadian_id')) {
            $query->where('jenis_kejadian_id', $request->jenis_kejadian_id);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tanggal_kejadian',
                '>=',
                $request->tanggal_mulai
            );
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate(
                'tanggal_kejadian',
                '<=',
                $request->tanggal_selesai
            );
        }

        $incidents = $query
            ->latest('tanggal_kejadian')
            ->latest('waktu_kejadian')
            ->paginate(10)
            ->withQueryString();

        $kecamatans = Kecamatan::orderBy('nama')->get();

        $jenisKejadians = JenisKejadian::where('aktif', true)
            ->orderBy('nama')
            ->get();

        return view('admin.incidents.index', compact(
            'incidents',
            'kecamatans',
            'jenisKejadians'
        ), [
            'title' => 'Data Kejadian',
            'search' => $request->search,
            'filterKecamatan' => $request->kecamatan_id,
            'filterJenisKejadian' => $request->jenis_kejadian_id,
            'filterStatus' => $request->status,
            'filterTanggalMulai' => $request->tanggal_mulai,
            'filterTanggalSelesai' => $request->tanggal_selesai
        ]);
    }

    /**
     * Form tambah incident.
     */
    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama')->get();

        $kelurahans = Kelurahan::orderBy('nama')->get();

        $jenisKejadians = JenisKejadian::where('aktif', true)
            ->with([
                'kategoris' => function ($query) {
                    $query->where('aktif', true)
                        ->orderBy('nama');
                }
            ])
            ->orderBy('nama')
            ->get();

        return view('admin.incidents.create', compact(
            'kecamatans',
            'kelurahans',
            'jenisKejadians'
        ), [
            'title' => 'Tambah Data Kejadian'
        ]);
    }
    /**
     * Simpan incident baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kecamatan_id' => [
                'required',
                'exists:kecamatans,id'
            ],

            'kelurahan_id' => [
                'required',
                'exists:kelurahans,id'
            ],

            'jenis_kejadian_id' => [
                'required',
                'exists:jenis_kejadian,id'
            ],

            'kategori_kejadian_id' => [
                'required',
                'exists:kategori_kejadian,id'
            ],

            'tanggal_kejadian' => [
                'required',
                'date'
            ],

            'waktu_kejadian' => [
                'required',
                'date_format:H:i'
            ],

            'alamat' => [
                'required',
                'string',
                'max:255'
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90'
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180'
            ],

            'deskripsi' => [
                'nullable',
                'string'
            ],

            'jumlah_korban' => [
                'required',
                'integer',
                'min:0'
            ],

            'jumlah_meninggal' => [
                'required',
                'integer',
                'min:0'
            ],

            'jumlah_luka' => [
                'required',
                'integer',
                'min:0'
            ],

            'estimasi_kerugian' => [
                'required',
                'numeric',
                'min:0'
            ],

            'status' => [
                'required',
                Rule::in([
                    'dilaporkan',
                    'ditangani',
                    'selesai'
                ])
            ],

            'keterangan' => [
                'nullable',
                'string'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Pastikan Kelurahan sesuai dengan Kecamatan
        |--------------------------------------------------------------------------
        */
        $kelurahanValid = Kelurahan::where('id', $request->kelurahan_id)
            ->where('kecamatan_id', $request->kecamatan_id)
            ->exists();

        if (!$kelurahanValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'kelurahan_id' => 'Kelurahan tidak sesuai dengan Kecamatan yang dipilih.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan Kategori sesuai dengan Jenis Kejadian
        |--------------------------------------------------------------------------
        */
        $kategoriValid = KategoriKejadian::where('id', $request->kategori_kejadian_id)
            ->where('jenis_kejadian_id', $request->jenis_kejadian_id)
            ->exists();

        if (!$kategoriValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'kategori_kejadian_id' => 'Kategori tidak sesuai dengan Jenis Kejadian yang dipilih.'
                ]);
        }

        $validated['user_id'] = Auth::id();

        Incident::create($validated);

        return redirect()
            ->route('incidents.index')
            ->with('success', 'Data incident berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail incident.
     */
    public function show(Incident $incident)
    {
        $incident->load([
            'kecamatan',
            'kelurahan',
            'jenisKejadian',
            'kategoriKejadian',
            'user'
        ]);

        return view('admin.incidents.show', compact('incident'), [
            'title' => 'Detail Data Kejadian'
        ]);
    }

    /**
     * Form edit incident.
     */
    public function edit(Incident $incident)
    {
        /*
        |--------------------------------------------------------------------------
        | Petugas hanya boleh edit incident miliknya
        |--------------------------------------------------------------------------
        */
        if (
            Auth::user()->role === 'petugas' &&
            $incident->user_id !== Auth::id()
        ) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah incident ini.');
        }

        $kecamatans = Kecamatan::orderBy('nama')->get();

        $kelurahans = Kelurahan::where(
            'kecamatan_id',
            $incident->kecamatan_id
        )
            ->orderBy('nama')
            ->get();

        $jenisKejadians = JenisKejadian::where('aktif', true)
            ->with([
                'kategoris' => function ($query) {
                    $query->where('aktif', true)
                        ->orderBy('nama');
                }
            ])
            ->orderBy('nama')
            ->get();

        return view('admin.incidents.edit', compact(
            'incident',
            'kecamatans',
            'kelurahans',
            'jenisKejadians'
        ), [
            'title' => 'Edit Data Kejadian'
        ]);
    }

    /**
     * Update incident.
     */
    public function update(Request $request, Incident $incident)
    {
        /*
        |--------------------------------------------------------------------------
        | Petugas hanya boleh update miliknya
        |--------------------------------------------------------------------------
        */
        if (
            Auth::user()->role === 'petugas' &&
            $incident->user_id !== Auth::id()
        ) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah incident ini.');
        }

        $validated = $request->validate([
            'kecamatan_id' => [
                'required',
                'exists:kecamatans,id'
            ],

            'kelurahan_id' => [
                'required',
                'exists:kelurahans,id'
            ],

            'jenis_kejadian_id' => [
                'required',
                'exists:jenis_kejadian,id'
            ],

            'kategori_kejadian_id' => [
                'required',
                'exists:kategori_kejadian,id'
            ],

            'tanggal_kejadian' => [
                'required',
                'date'
            ],

            'waktu_kejadian' => [
                'required',
                'date_format:H:i'
            ],

            'alamat' => [
                'required',
                'string',
                'max:255'
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90'
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180'
            ],

            'deskripsi' => [
                'nullable',
                'string'
            ],

            'jumlah_korban' => [
                'required',
                'integer',
                'min:0'
            ],

            'jumlah_meninggal' => [
                'required',
                'integer',
                'min:0'
            ],

            'jumlah_luka' => [
                'required',
                'integer',
                'min:0'
            ],

            'estimasi_kerugian' => [
                'required',
                'numeric',
                'min:0'
            ],

            'status' => [
                'required',
                Rule::in([
                    'dilaporkan',
                    'ditangani',
                    'selesai'
                ])
            ],

            'keterangan' => [
                'nullable',
                'string'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi relasi Kelurahan
        |--------------------------------------------------------------------------
        */
        $kelurahanValid = Kelurahan::where('id', $request->kelurahan_id)
            ->where('kecamatan_id', $request->kecamatan_id)
            ->exists();

        if (!$kelurahanValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'kelurahan_id' => 'Kelurahan tidak sesuai dengan Kecamatan yang dipilih.'
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi relasi Kategori
        |--------------------------------------------------------------------------
        */
        $kategoriValid = KategoriKejadian::where('id', $request->kategori_kejadian_id)
            ->where('jenis_kejadian_id', $request->jenis_kejadian_id)
            ->exists();

        if (!$kategoriValid) {
            return back()
                ->withInput()
                ->withErrors([
                    'kategori_kejadian_id' => 'Kategori tidak sesuai dengan Jenis Kejadian yang dipilih.'
                ]);
        }

        $incident->update($validated);

        return redirect()
            ->route('incidents.index')
            ->with('success', 'Data incident berhasil diperbarui.');
    }

    /**
     * Hapus incident.
     */
    public function destroy(Incident $incident)
    {
        /*
        |--------------------------------------------------------------------------
        | Pimpinan tidak boleh hapus
        |--------------------------------------------------------------------------
        */
        if (Auth::user()->role === 'pimpinan') {
            abort(403, 'Pimpinan tidak memiliki akses untuk menghapus incident.');
        }

        /*
        |--------------------------------------------------------------------------
        | Petugas hanya boleh hapus miliknya
        |--------------------------------------------------------------------------
        */
        if (
            Auth::user()->role === 'petugas' &&
            $incident->user_id !== Auth::id()
        ) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus incident ini.');
        }

        $incident->delete();

        return redirect()
            ->route('incidents.index')
            ->with('success', 'Data incident berhasil dihapus.');
    }
}