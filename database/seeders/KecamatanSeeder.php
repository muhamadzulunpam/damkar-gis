<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kecamatans = [
            [
                'nama' => 'Ciputat',
                'keterangan' => 'Kecamatan Ciputat',
            ],
            [
                'nama' => 'Ciputat Timur',
                'keterangan' => 'Kecamatan Ciputat Timur',
            ],
            [
                'nama' => 'Pamulang',
                'keterangan' => 'Kecamatan Pamulang',
            ],
            [
                'nama' => 'Setu',
                'keterangan' => 'Kecamatan Setu',
            ],
            [
                'nama' => 'Serpong',
                'keterangan' => 'Kecamatan Serpong',
            ],
            [
                'nama' => 'Serpong Utara',
                'keterangan' => 'Kecamatan Serpong Utara',
            ],
            [
                'nama' => 'Pondok Aren',
                'keterangan' => 'Kecamatan Pondok Aren',
            ],
        ];

        foreach ($kecamatans as $kecamatan) {
            Kecamatan::create($kecamatan);
        }
    }
}
