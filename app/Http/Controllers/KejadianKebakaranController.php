<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\KejadianKebakaran;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;

class KejadianKebakaranController extends Controller
{
    /**
     * Menampilkan daftar kejadian kebakaran.
     */
    public function index(Request $request)
    {
        $query = KejadianKebakaran::with([
            'kecamatan',
            'kelurahan',
        ]);

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id_kejadian', 'like', '%' . $search . '%')
                    ->orWhere('alamat_lengkap', 'like', '%' . $search . '%')
                    ->orWhere('nama_pemilik', 'like', '%' . $search . '%')
                    ->orWhere('jenis_kejadian', 'like', '%' . $search . '%')
                    ->orWhere('kategori_jenis', 'like', '%' . $search . '%');
            });
        }

        // Filter kecamatan
        if ($request->filled('kecamatan_id')) {
            $query->where('kecamatan_id', $request->kecamatan_id);
        }

        // Filter kategori
        if ($request->filled('kategori_jenis')) {
            $query->where(
                'kategori_jenis',
                $request->kategori_jenis
            );
        }

        // Filter tanggal mulai
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate(
                'tanggal',
                '>=',
                $request->tanggal_mulai
            );
        }

        // Filter tanggal selesai
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate(
                'tanggal',
                '<=',
                $request->tanggal_selesai
            );
        }

        $kejadianKebakarans = $query
            ->latest('tanggal')
            ->latest('waktu_kejadian')
            ->paginate(10)
            ->withQueryString();

        $kecamatans = Kecamatan::orderBy('nama')->get();

        $kategoriJenis = KejadianKebakaran::query()
            ->select('kategori_jenis')
            ->whereNotNull('kategori_jenis')
            ->where('kategori_jenis', '!=', '')
            ->distinct()
            ->orderBy('kategori_jenis')
            ->pluck('kategori_jenis');
        $title = 'Daftar Kejadian Kebakaran';

        return view('admin.kejadian-kebakaran.index', compact(
            'kejadianKebakarans',
            'kecamatans',
            'kategoriJenis',
            'title'
        ));
    }

    /**
     * Form tambah kejadian kebakaran.
     */
    public function create()
    {
        $kecamatans = Kecamatan::with('kelurahans')
            ->orderBy('nama')
            ->get();

        $regus = \App\Models\Regu::orderBy('nama')->get();
        $previewId = KejadianKebakaran::generateIdKejadian();

        $title = 'Tambah Kejadian Kebakaran';

        return view('admin.kejadian-kebakaran.create', compact(
            'kecamatans',
            'previewId',
            'title',
            'regus'
        ));
    }

    /**
     * Menyimpan kejadian kebakaran.
     */
    public function store(Request $request)
    {
        $tanggal = strtotime((string) $request->tanggal) ? $request->tanggal : null;

        $request->merge([
            'id_kejadian' => KejadianKebakaran::generateIdKejadian($tanggal),
        ]);
        $validated = $this->validateData($request);

        // Pastikan kelurahan berada di kecamatan yang dipilih.
        $kelurahan = Kelurahan::where('id', $validated['kelurahan_id'])
            ->where('kecamatan_id', $validated['kecamatan_id'])
            ->first();

        if (!$kelurahan) {
            return back()
                ->withInput()
                ->withErrors([
                    'kelurahan_id' =>
                        'Kelurahan tidak sesuai dengan kecamatan yang dipilih.'
                ]);
        }
        $validated['durasi_menit'] = KejadianKebakaran::hitungDurasiMenit(
            $validated['waktu_kejadian'] ?? null,
            $validated['waktu_tiba'] ?? null
        );

        $kejadianKebakaran = KejadianKebakaran::create($validated);

        ActivityLogger::log(
            'Create',
            'Menambahkan Kejadian Kebakaran "' . $kejadianKebakaran->id_kejadian . '".',
            'Kejadian Kebakaran',
            'create'
        );

        return redirect()
            ->route('kejadian-kebakarans.index')
            ->with('success', 'Data kejadian kebakaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail kejadian.
     */
    public function show(KejadianKebakaran $kejadianKebakaran)
    {
        $kejadianKebakaran->load([
            'kecamatan',
            'kelurahan',
            'regu',
        ]);

        $title = 'Detail Kejadian Kebakaran';
        return view(
            'admin.kejadian-kebakaran.show',
            compact('kejadianKebakaran', 'title')
        );
    }

    /**
     * Form edit kejadian.
     */
    public function edit(KejadianKebakaran $kejadianKebakaran)
    {
        $kecamatans = Kecamatan::with('kelurahans')
            ->orderBy('nama')
            ->get();

        $regus = \App\Models\Regu::orderBy('nama')->get();

        $title = 'Edit Kejadian Kebakaran';

        return view(
            'admin.kejadian-kebakaran.edit',
            compact(
                'kejadianKebakaran',
                'kecamatans',
                'title',
                'regus'
            )
        );
    }

    /**
     * Update kejadian.
     */
    public function update(
        Request $request,
        KejadianKebakaran $kejadianKebakaran
    ) {
        // ID kejadian tidak boleh diubah, ambil dari data yang tersimpan
        $request->merge([
            'id_kejadian' => $kejadianKebakaran->id_kejadian,
        ]);

        $validated = $this->validateData(
            $request,
            $kejadianKebakaran->id
        );

        // Pastikan kelurahan sesuai dengan kecamatan.
        $kelurahan = Kelurahan::where('id', $validated['kelurahan_id'])
            ->where('kecamatan_id', $validated['kecamatan_id'])
            ->first();

        if (!$kelurahan) {
            return back()
                ->withInput()
                ->withErrors([
                    'kelurahan_id' =>
                        'Kelurahan tidak sesuai dengan kecamatan yang dipilih.'
                ]);
        }

        // Hitung ulang durasi (di kode lama, durasi tidak ikut diperbarui saat edit)
        $validated['durasi_menit'] = KejadianKebakaran::hitungDurasiMenit(
            $validated['waktu_kejadian'] ?? null,
            $validated['waktu_tiba'] ?? null
        );

        $kejadianKebakaran->update($validated);
        
        ActivityLogger::log(
            'Update',
            'Memperbarui Kejadian Kebakaran "' . $kejadianKebakaran->id_kejadian . '".',
            'Kejadian Kebakaran',
            'update'
        );

        return redirect()
            ->route('kejadian-kebakarans.index')
            ->with('success', 'Data kejadian kebakaran berhasil diperbarui.');
    }

    /**
     * Menghapus kejadian.
     */
    public function destroy(KejadianKebakaran $kejadianKebakaran)
    {
        $kejadianKebakaran->delete();

        ActivityLogger::log(
            'Delete',
            'Menghapus Kejadian Kebakaran "' . $kejadianKebakaran->id_kejadian . '".',
            'Kejadian Kebakaran',
            'delete'
        );
        
        return redirect()
            ->route('kejadian-kebakarans.index')
            ->with('success', 'Data kejadian kebakaran berhasil dihapus.');
    }

    /**
     * Validasi data.
     */
    private function validateData(
        Request $request,
        ?int $id = null
    ): array {
        return $request->validate([
            'id_kejadian' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kejadian_kebakarans', 'id_kejadian')
                    ->ignore($id),
            ],

            'kecamatan_id' => [
                'required',
                'exists:kecamatans,id',
            ],

            'kelurahan_id' => [
                'required',
                'exists:kelurahans,id',
            ],

            'alamat_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_pemilik' => [
                'nullable',
                'string',
                'max:150',
            ],

            'tanggal' => [
                'required',
                'date',
            ],

            'waktu_kejadian' => [
                'required',
                'date_format:H:i',
            ],

            'waktu_tiba' => [
                'nullable',
                'date_format:H:i',
            ],

            'waktu_selesai' => [
                'nullable',
                'date_format:H:i',
            ],
            'jenis_kejadian' => [
                'required',
                'string',
                'max:100',
            ],

            'kategori_jenis' => [
                'required',
                'string',
                'max:100',
            ],
            'kelompok_kebakaran' => [
                'required',
                Rule::in([
                    'Pemukiman / Properti',
                    'Komersial / Industri',
                    'Instalasi / Non-Bangunan',
                    'Kebakaran Lainnya',
                ]),
            ],

            'penyebab' => [
                'nullable',
                'string',
                'max:255',
            ],

            'luas_area_m2' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'kerugian_harta' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'kerugian_benda' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'korban_jiwa' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'jumlah_unit' => [
                'required',
                'integer',
                'min:0',
            ],

            'regu_id' => [
                'nullable',
                'exists:regus,id',
            ],

            'keterangan_korban' => [
                'nullable',
                'string',
            ]
        ]);
    }
}