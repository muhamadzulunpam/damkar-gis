<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KategoriKejadian;
use App\Models\JenisKejadian;

class KategoriKejadianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Kebakaran' => [
                [
                    'nama' => 'Rumah',
                    'keterangan' => 'Kebakaran pada rumah atau tempat tinggal.',
                ],
                [
                    'nama' => 'Gedung/Bangunan',
                    'keterangan' => 'Kebakaran pada gedung atau bangunan.',
                ],
                [
                    'nama' => 'Kendaraan',
                    'keterangan' => 'Kebakaran pada kendaraan.',
                ],
                [
                    'nama' => 'Lahan',
                    'keterangan' => 'Kebakaran pada lahan atau area terbuka.',
                ],
                [
                    'nama' => 'Sampah',
                    'keterangan' => 'Kebakaran yang terjadi pada tumpukan sampah.',
                ],
                [
                    'nama' => 'Instalasi Listrik',
                    'keterangan' => 'Kebakaran yang berkaitan dengan instalasi atau jaringan listrik.',
                ],
            ],

            'Penyelamatan' => [
                [
                    'nama' => 'Evakuasi Manusia',
                    'keterangan' => 'Penyelamatan atau evakuasi manusia dari kondisi darurat.',
                ],
                [
                    'nama' => 'Evakuasi Hewan',
                    'keterangan' => 'Penyelamatan atau evakuasi hewan.',
                ],
                [
                    'nama' => 'Orang Terjebak',
                    'keterangan' => 'Penyelamatan orang yang terjebak pada suatu lokasi.',
                ],
                [
                    'nama' => 'Kecelakaan',
                    'keterangan' => 'Penyelamatan akibat kecelakaan.',
                ],
            ],

            'Bencana Alam' => [
                [
                    'nama' => 'Banjir',
                    'keterangan' => 'Penanganan kejadian banjir.',
                ],
                [
                    'nama' => 'Pohon Tumbang',
                    'keterangan' => 'Penanganan pohon tumbang yang membahayakan.',
                ],
                [
                    'nama' => 'Longsor',
                    'keterangan' => 'Penanganan kejadian tanah longsor.',
                ],
                [
                    'nama' => 'Angin Kencang',
                    'keterangan' => 'Penanganan dampak angin kencang.',
                ],
            ],

            'Evakuasi' => [
                [
                    'nama' => 'Evakuasi Warga',
                    'keterangan' => 'Evakuasi warga dari lokasi yang berbahaya.',
                ],
                [
                    'nama' => 'Evakuasi Hewan',
                    'keterangan' => 'Evakuasi hewan dari lokasi berbahaya.',
                ],
            ],

            'Lainnya' => [
                [
                    'nama' => 'Lain-lain',
                    'keterangan' => 'Kejadian lain yang membutuhkan penanganan petugas.',
                ],
            ],
        ];

        foreach ($data as $jenisNama => $kategoris) {

            $jenis = JenisKejadian::where(
                'nama',
                $jenisNama
            )->first();

            if (!$jenis) {
                continue;
            }

            foreach ($kategoris as $kategori) {

                KategoriKejadian::updateOrCreate(
                    [
                        'jenis_kejadian_id' => $jenis->id,
                        'nama' => $kategori['nama'],
                    ],
                    [
                        'keterangan' => $kategori['keterangan'],
                        'aktif' => true,
                    ]
                );
            }
        }
    
    }
}
