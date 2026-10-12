<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisKejadian;


class JenisKejadianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Kebakaran',
                'keterangan' => 'Kejadian yang berkaitan dengan kebakaran pada bangunan, kendaraan, lahan, maupun objek lainnya.',
                'aktif' => true,
            ],

            [
                'nama' => 'Penyelamatan',
                'keterangan' => 'Kejadian yang membutuhkan tindakan penyelamatan terhadap manusia, hewan, maupun kondisi darurat lainnya.',
                'aktif' => true,
            ],

            [
                'nama' => 'Bencana Alam',
                'keterangan' => 'Kejadian akibat bencana alam yang membutuhkan penanganan atau bantuan petugas.',
                'aktif' => true,
            ],

            [
                'nama' => 'Evakuasi',
                'keterangan' => 'Kegiatan evakuasi manusia, hewan, atau objek dari kondisi berbahaya.',
                'aktif' => true,
            ],

            [
                'nama' => 'Lainnya',
                'keterangan' => 'Jenis kejadian lainnya yang membutuhkan penanganan petugas Damkar.',
                'aktif' => true,
            ],
        ];

        foreach ($data as $item) {
            JenisKejadian::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
