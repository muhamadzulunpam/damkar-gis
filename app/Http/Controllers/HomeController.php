<?php

namespace App\Http\Controllers;

use App\Models\KMeansRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil kombinasi jenis_kejadian + periode yang SUDAH dianalisis
        |--------------------------------------------------------------------------
        */

        $runs = KMeansRun::query()
            ->select('jenis_kejadian', 'tahun_awal', 'tahun_akhir')
            ->whereNotNull('jenis_kejadian')
            ->whereNotNull('tahun_awal')
            ->whereNotNull('tahun_akhir')
            ->distinct()
            ->orderBy('jenis_kejadian')
            ->orderByDesc('tahun_akhir')
            ->orderByDesc('tahun_awal')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Daftar jenis (unik)
        |--------------------------------------------------------------------------
        */

        $jenisKejadianList = $runs
            ->pluck('jenis_kejadian')
            ->filter()
            ->unique()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Peta periode per jenis
        | Format: [
        |   'kebakaran' => [
        |       ['value' => '2022/2025', 'label' => '2022/2025 (4 tahun)'],
        |       ['value' => '2022/2023', 'label' => '2022/2023 (2 tahun)'],
        |   ],
        |   ...
        | ]
        |--------------------------------------------------------------------------
        */

        $periodePerJenis = $runs
            ->groupBy('jenis_kejadian')
            ->map(function ($items) {
                return $items
                    ->map(function ($r) {
                        $awal  = (int) $r->tahun_awal;
                        $akhir = (int) $r->tahun_akhir;

                        return [
                            'value' => "{$awal}/{$akhir}",
                            'label' => $awal === $akhir
                                ? (string) $awal
                                : "{$awal}/{$akhir} (" . ($akhir - $awal + 1) . ' tahun)',
                        ];
                    })
                    ->unique('value')
                    ->values()
                    ->all();
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | Default: jenis pertama + periode pertama dari jenis tersebut
        |--------------------------------------------------------------------------
        */

        $jenisDefault   = $jenisKejadianList->first();
        $periodeDefault = $jenisDefault
            ? ($periodePerJenis[$jenisDefault][0]['value'] ?? null)
            : null;

        $edukasi = $this->dataEdukasi();

        return view('homePage', compact(
            'jenisKejadianList',
            'periodePerJenis',
            'jenisDefault',
            'periodeDefault',
            'edukasi'
        ));
    }

    /**
     * JSON hasil K-Means untuk peta (dipanggil AJAX).
     *
     * Parameter:
     *   - periode       : "2022/2025" (wajib)
     *   - jenis_kejadian: "kebakaran" | "penyelamatan"
     */
    public function petaData(Request $request)
    {
        $periode = $request->input('periode');
        $jenis   = $request->input('jenis_kejadian');

        if (! $periode || ! preg_match('/^\d{4}\/\d{4}$/', $periode)) {
            return response()->json([
                'ada'       => false,
                'hasil'     => (object) [],
                'statistik' => ['rendah' => 0, 'sedang' => 0, 'tinggi' => 0],
            ]);
        }

        [$awal, $akhir] = array_map('intval', explode('/', $periode));

        $query = KMeansRun::query()
            ->with('results.kelurahan.kecamatan')
            ->where('tahun_awal', $awal)
            ->where('tahun_akhir', $akhir);

        $jenis !== null && $jenis !== ''
            ? $query->where('jenis_kejadian', $jenis)
            : $query->whereNull('jenis_kejadian');

        $run = $query->latest('id')->first();

        if (! $run) {
            return response()->json([
                'ada'       => false,
                'hasil'     => (object) [],
                'statistik' => ['rendah' => 0, 'sedang' => 0, 'tinggi' => 0],
            ]);
        }

        $hasil     = [];
        $statistik = ['rendah' => 0, 'sedang' => 0, 'tinggi' => 0];

        foreach ($run->results as $result) {
            $kel = $result->kelurahan;
            if (! $kel) {
                continue;
            }

            $key = strtolower(trim($kel->nama)) . '|'
                 . strtolower(trim($kel->kecamatan?->nama ?? ''));

            $hasil[$key] = [
                'jumlah_kejadian'   => $result->jumlah_kejadian,
                'tingkat_kerawanan' => $result->tingkat_kerawanan,
                'warna'             => $result->warna,
            ];

            $tingkat = strtolower($result->tingkat_kerawanan);
            if (isset($statistik[$tingkat])) {
                $statistik[$tingkat]++;
            }
        }

        return response()->json([
            'ada'        => true,
            'hasil'      => $hasil,
            'statistik'  => $statistik,
            'diperbarui' => $run->created_at->translatedFormat('d F Y'),
        ]);
    }

    /**
     * JSON boundary kelurahan Tangsel (di-cache 1 hari).
     */
    public function boundary()
    {
        $data = Cache::get('boundary_tangsel');

        if (! $data) {
            try {
                $response = Http::timeout(30)->get(
                    'https://wilayah.smartartstudio.my.id/api/boundaries/36.74/children'
                );

                if ($response->successful()) {
                    $all = collect($response->json());

                    $kecamatan = $all
                        ->filter(fn ($i) => preg_match('/^36\.74\.\d{2}$/', $i['kode'] ?? ''))
                        ->keyBy('kode');

                    $data = $all
                        ->filter(fn ($i) => preg_match('/^36\.74\.\d{2}\.\d{4}$/', $i['kode'] ?? ''))
                        ->map(function ($i) use ($kecamatan) {
                            $kodeKec = implode('.', array_slice(explode('.', $i['kode']), 0, 3));

                            return [
                                'nama'      => $i['nama'],
                                'kecamatan' => $kecamatan[$kodeKec]['nama'] ?? '-',
                                'path'      => $i['path'] ?? [],
                            ];
                        })
                        ->values()
                        ->toArray();

                    Cache::put('boundary_tangsel', $data, now()->addDay());
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return response()->json($data ?? []);
    }

    /**
     * Data edukasi statis.
     */
    private function dataEdukasi(): array
    {
        return [
            [
                'id' => 1, 'kategori' => 'api', 'label' => 'Api', 'ikon' => 'fa-fire',
                'judul' => 'Cara Memadamkan Api',
                'deskripsi' => 'Kenali jenis kebakaran dan cara penanganan yang aman serta tepat.',
                'menit' => 5, 'penting' => true, 'gambar' => 'img/info/edukasi_api.jpeg',
                'langkah' => [
                    'Tetap tenang dan segera beri tahu orang di sekitar.',
                    'Matikan sumber listrik atau gas jika aman dilakukan.',
                    'Padamkan api kecil dengan APAR, karung basah, atau selimut tahan api.',
                    'Jika api membesar, segera menjauh dan hubungi Damkar 0811 9000 74 atau 112.',
                ],
            ],
            [
                'id' => 2, 'kategori' => 'api', 'label' => 'Api', 'ikon' => 'fa-fire',
                'judul' => 'Mencegah Kebakaran di Rumah',
                'deskripsi' => 'Kebiasaan sederhana di rumah yang dapat mencegah kebakaran.',
                'menit' => 4, 'penting' => false, 'gambar' => 'img/info/edukasi_api.jpeg',
                'langkah' => [
                    'Cabut charger dan peralatan listrik yang tidak digunakan.',
                    'Jangan menumpuk colokan pada satu stop kontak.',
                    'Periksa selang dan regulator gas secara berkala.',
                    'Jangan meninggalkan kompor atau setrika dalam keadaan menyala.',
                ],
            ],
            [
                'id' => 3, 'kategori' => 'apar', 'label' => 'APAR', 'ikon' => 'fa-fire-extinguisher',
                'judul' => 'Menggunakan APAR dengan Metode PASS',
                'deskripsi' => 'Langkah singkat memakai Alat Pemadam Api Ringan saat api masih kecil.',
                'menit' => 3, 'penting' => true, 'gambar' => 'img/info/edukasi_apar.jpeg',
                'langkah' => [
                    'Pull: tarik pin pengaman APAR.',
                    'Aim: arahkan nozzle ke pangkal api, bukan ke nyala api.',
                    'Squeeze: tekan tuas pegangan.',
                    'Sweep: sapukan dari sisi ke sisi hingga api padam.',
                ],
            ],
            [
                'id' => 4, 'kategori' => 'apar', 'label' => 'APAR', 'ikon' => 'fa-fire-extinguisher',
                'judul' => 'Perawatan & Penempatan APAR',
                'deskripsi' => 'Pastikan APAR selalu siap pakai dan mudah dijangkau.',
                'menit' => 4, 'penting' => false, 'gambar' => 'img/info/edukasi_apar.jpeg',
                'langkah' => [
                    'Letakkan APAR di tempat mudah terlihat dan dijangkau.',
                    'Periksa jarum indikator tekanan pada zona hijau.',
                    'Cek masa berlaku dan segel pin secara rutin.',
                    'Isi ulang APAR setelah dipakai, walaupun hanya sebentar.',
                ],
            ],
            [
                'id' => 5, 'kategori' => 'evakuasi', 'label' => 'Evakuasi', 'ikon' => 'fa-people-arrows',
                'judul' => 'Evakuasi Saat Kebakaran Gedung',
                'deskripsi' => 'Langkah menyelamatkan diri dari gedung yang terbakar.',
                'menit' => 6, 'penting' => true, 'gambar' => 'img/info/edukasi_evakuasi.jpeg',
                'langkah' => [
                    'Bunyikan alarm dan keluar melalui jalur evakuasi terdekat.',
                    'Jangan gunakan lift, gunakan tangga darurat.',
                    'Merunduk dan tutup hidung dengan kain basah jika ada asap.',
                    'Berkumpul di titik kumpul dan laporkan diri kepada petugas.',
                ],
            ],
            [
                'id' => 6, 'kategori' => 'evakuasi', 'label' => 'Evakuasi', 'ikon' => 'fa-people-arrows',
                'judul' => 'Jika Pakaian Terbakar',
                'deskripsi' => 'Tindakan cepat yang bisa mengurangi tingkat luka bakar.',
                'menit' => 3, 'penting' => false, 'gambar' => 'img/info/edukasi_evakuasi.jpeg',
                'langkah' => [
                    'Stop: berhenti dan jangan berlari.',
                    'Drop: jatuhkan diri ke lantai.',
                    'Roll: berguling perlahan untuk memadamkan api.',
                    'Siram luka dengan air mengalir dan segera cari bantuan medis.',
                ],
            ],
            [
                'id' => 7, 'kategori' => 'hewan', 'label' => 'Hewan', 'ikon' => 'fa-paw',
                'judul' => 'Ular Masuk Rumah, Apa yang Harus Dilakukan?',
                'deskripsi' => 'Cara aman menghadapi ular sebelum petugas rescue tiba.',
                'menit' => 4, 'penting' => false, 'gambar' => 'img/info/edukasi_hewan.jpeg',
                'langkah' => [
                    'Jauhi ular dan jangan mencoba menangkap atau memukulnya.',
                    'Jauhkan anak-anak dan hewan peliharaan dari area tersebut.',
                    'Awasi dari jarak aman agar mudah menunjukkan lokasinya.',
                    'Hubungi Damkar 0811 9000 74 untuk evakuasi.',
                ],
            ],
            [
                'id' => 8, 'kategori' => 'hewan', 'label' => 'Hewan', 'ikon' => 'fa-paw',
                'judul' => 'Sarang Tawon di Sekitar Rumah',
                'deskripsi' => 'Jangan asal membakar atau mengusik sarang tawon.',
                'menit' => 3, 'penting' => false, 'gambar' => 'img/info/edukasi_hewan.jpeg',
                'langkah' => [
                    'Jangan melempar atau membakar sarang tawon.',
                    'Batasi akses ke area sarang, terutama untuk anak-anak.',
                    'Jika tersengat, cuci area sengatan dan kompres dingin.',
                    'Segera ke fasilitas kesehatan jika muncul sesak atau bengkak berat, lalu hubungi Damkar untuk pemindahan sarang.',
                ],
            ],
        ];
    }
}