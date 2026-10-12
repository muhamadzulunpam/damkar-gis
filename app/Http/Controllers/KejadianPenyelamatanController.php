<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\KejadianPenyelamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogger;

class KejadianPenyelamatanController extends Controller
{
    public function index(Request $request)
    {
        $query = KejadianPenyelamatan::with([
            'kecamatan',
            'kelurahan',
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'id_penyelamatan',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'alamat_lengkap',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'nama_pemilik',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'jenis_kejadian',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'kategori_jenis',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'kelompok_penyelamatan',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        // Filter Kecamatan
        if ($request->filled('kecamatan_id')) {
            $query->where(
                'kecamatan_id',
                $request->kecamatan_id
            );
        }

        // Filter Kategori
        if ($request->filled('kategori_jenis')) {
            $query->where(
                'kategori_jenis',
                $request->kategori_jenis
            );
        }

        // Filter Kelompok
        if ($request->filled('kelompok_penyelamatan')) {
            $query->where(
                'kelompok_penyelamatan',
                $request->kelompok_penyelamatan
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

        $kejadianPenyelamatans = $query
            ->latest('tanggal')
            ->latest('waktu_kejadian')
            ->paginate(10)
            ->withQueryString();

        $kecamatans = Kecamatan::orderBy('nama')->get();

        $kategoriJenis = KejadianPenyelamatan::query()
            ->select('kategori_jenis')
            ->whereNotNull('kategori_jenis')
            ->where('kategori_jenis', '!=', '')
            ->distinct()
            ->orderBy('kategori_jenis')
            ->pluck('kategori_jenis');

        $kelompokPenyelamatan = KejadianPenyelamatan::query()
            ->select('kelompok_penyelamatan')
            ->whereNotNull('kelompok_penyelamatan')
            ->where('kelompok_penyelamatan', '!=', '')
            ->distinct()
            ->orderBy('kelompok_penyelamatan')
            ->pluck('kelompok_penyelamatan');

        $title = 'Kejadian Penyelamatan';

        return view(
            'admin.kejadian-penyelamatan.index',
            compact(
                'kejadianPenyelamatans',
                'kecamatans',
                'kategoriJenis',
                'kelompokPenyelamatan',
                'title'
            )
        );
    }

    public function create()
    {
        $kecamatans = Kecamatan::with('kelurahans')
            ->orderBy('nama')
            ->get();
        
        $regus = \App\Models\Regu::orderBy('nama')->get();
        $previewId = KejadianPenyelamatan::generateIdPenyelamatan();
        $title = 'Tambah Kejadian Penyelamatan';

        return view(
            'admin.kejadian-penyelamatan.create',
            compact('kecamatans', 'previewId', 'title', 'regus')
        );
    }

    public function store(Request $request)
    {
        // 1. Buat ID SEBELUM validasi
        $tanggal = strtotime((string) $request->tanggal) ? $request->tanggal : null;

        $request->merge([
            'id_penyelamatan' => KejadianPenyelamatan::generateIdPenyelamatan($tanggal),
        ]);

        // 2. Baru validasi
        $validated = $this->validateData($request);

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

        // 3. Hitung durasi di server dan pastikan kolom angka tidak NULL
        $validated['durasi_menit'] = KejadianPenyelamatan::hitungDurasiMenit(
            $validated['waktu_kejadian'] ?? null,
            $validated['waktu_tiba'] ?? null
        );
        $validated['kerugian_harta'] = $validated['kerugian_harta'] ?? 0;
        $validated['korban_jiwa'] = $validated['korban_jiwa'] ?? 0;

        $kejadianPenyelamatan = KejadianPenyelamatan::create($validated);

        ActivityLogger::log(
            'Create',
            'Menambahkan Kejadian Penyelamatan "' . $kejadianPenyelamatan->id_penyelamatan . '".',
            'Kejadian Penyelamatan',
            'create'
        );

        return redirect()
            ->route('kejadian-penyelamatans.index')
            ->with('success', 'Data kejadian penyelamatan berhasil ditambahkan.');
    }


    public function show(
        KejadianPenyelamatan $kejadianPenyelamatan
    ) {
        $kejadianPenyelamatan->load([
            'kecamatan',
            'kelurahan',
            'regu',
        ]);

        $title = 'Detail Kejadian Penyelamatan';
        return view(
            'admin.kejadian-penyelamatan.show',
            compact('kejadianPenyelamatan', 'title')
        );
    }

    public function edit(
        KejadianPenyelamatan $kejadianPenyelamatan
    ) {
        $kecamatans = Kecamatan::with('kelurahans')
            ->orderBy('nama')
            ->get();
        $regus = \App\Models\Regu::orderBy('nama')->get();
        $title = 'Edit Kejadian Penyelamatan';
        return view(
            'admin.kejadian-penyelamatan.edit',
            compact(
                'kejadianPenyelamatan',
                'kecamatans',
                'regus',
                'title'
            )
        );
    }
    public function update(
        Request $request,
        KejadianPenyelamatan $kejadianPenyelamatan
    ) {
        // ID tidak boleh berubah, ambil dari data tersimpan
        $request->merge([
            'id_penyelamatan' => $kejadianPenyelamatan->id_penyelamatan,
        ]);

        $validated = $this->validateData($request, $kejadianPenyelamatan->id);

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

        $validated['durasi_menit'] = KejadianPenyelamatan::hitungDurasiMenit(
            $validated['waktu_kejadian'] ?? null,
            $validated['waktu_tiba'] ?? null
        );
        $validated['kerugian_harta'] = $validated['kerugian_harta'] ?? 0;
        $validated['korban_jiwa'] = $validated['korban_jiwa'] ?? 0;

        $kejadianPenyelamatan->update($validated);

        ActivityLogger::log(
            'Update',
            'Memperbarui Kejadian Penyelamatan "' . $kejadianPenyelamatan->id_penyelamatan . '".',
            'Kejadian Penyelamatan',
            'update'
        );  

        return redirect()
            ->route('kejadian-penyelamatans.index')
            ->with('success', 'Data kejadian penyelamatan berhasil diperbarui.');
    }


    public function destroy(
        KejadianPenyelamatan $kejadianPenyelamatan
    ) {
        $kejadianPenyelamatan->delete();

        ActivityLogger::log(
            'Delete',
            'Menghapus Kejadian Penyelamatan "' . $kejadianPenyelamatan->id_penyelamatan . '".',
            'Kejadian Penyelamatan',
            'delete'
        );

        return redirect()
            ->route('kejadian-penyelamatans.index')
            ->with(
                'success',
                'Data kejadian penyelamatan berhasil dihapus.'
            );
    }

    private function validateData(
        Request $request,
        ?int $id = null
    ): array {
        return $request->validate([
            'id_penyelamatan' => [
                'required',
                'string',
                'max:50',
                Rule::unique(
                    'kejadian_penyelamatans',
                    'id_penyelamatan'
                )->ignore($id),
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

            'durasi_menit' => [
                'nullable',
                'integer',
                'min:0',
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

            'kelompok_penyelamatan' => [
                'required',
                Rule::in([
                    'Object Rescue',
                    'Animal Rescue',
                    'Disaster Rescue',
                    'Human Rescue',
                    'Penyelamatan Lainnya',
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

            'keterangan' => [
                'nullable',
                'string',
            ],
        ]);
    }
}