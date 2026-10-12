<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Database\Seeder;

class KelurahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

            // =====================================================
            // CIPUTAT - 7 KELURAHAN
            // =====================================================
            'Ciputat' => [
                [
                    'nama' => 'Cipayung',
                    'latitude' => -6.331389,
                    'longitude' => 106.752222,
                ],
                [
                    'nama' => 'Ciputat',
                    'latitude' => -6.318500,
                    'longitude' => 106.755500,
                ],
                [
                    'nama' => 'Jombang',
                    'latitude' => -6.314500,
                    'longitude' => 106.735500,
                ],
                [
                    'nama' => 'Sawah Baru',
                    'latitude' => -6.302500,
                    'longitude' => 106.746500,
                ],
                [
                    'nama' => 'Sawah',
                    'latitude' => -6.306500,
                    'longitude' => 106.755000,
                ],
                [
                    'nama' => 'Serua',
                    'latitude' => -6.326500,
                    'longitude' => 106.721500,
                ],
                [
                    'nama' => 'Serua Indah',
                    'latitude' => -6.332500,
                    'longitude' => 106.733500,
                ],
            ],

            // =====================================================
            // CIPUTAT TIMUR - 6 KELURAHAN
            // =====================================================
            'Ciputat Timur' => [
                [
                    'nama' => 'Cempaka Putih',
                    'latitude' => -6.302488, // UPDATE: Diperbaiki agar masuk wilayah Cempaka Putih asli
                    'longitude' => 106.759453,
                ],
                [
                    'nama' => 'Cireundeu',
                    'latitude' => -6.304500,
                    'longitude' => 106.774500,
                ],
                [
                    'nama' => 'Pisangan',
                    'latitude' => -6.320833,
                    'longitude' => 106.763611,
                ],
                [
                    'nama' => 'Pondok Ranji',
                    'latitude' => -6.276500,
                    'longitude' => 106.741500,
                ],
                [
                    'nama' => 'Rempoa',
                    'latitude' => -6.283500,
                    'longitude' => 106.757500,
                ],
                [
                    'nama' => 'Rengas',
                    'latitude' => -6.286500,
                    'longitude' => 106.770500,
                ],
            ],

            // =====================================================
            // PAMULANG - 8 KELURAHAN
            // =====================================================
            'Pamulang' => [
                [
                    'nama' => 'Bambu Apus',
                    'latitude' => -6.331500, // UPDATE: Koreksi pergeseran wilayah
                    'longitude' => 106.711500,
                ],
                [
                    'nama' => 'Benda Baru',
                    'latitude' => -6.346500, // UPDATE: Tukar ke posisi yang benar
                    'longitude' => 106.709500,
                ],
                [
                    'nama' => 'Kedaung',
                    'latitude' => -6.327500, // UPDATE: Reposisi dari Setu ke Kedaung
                    'longitude' => 106.741500,
                ],
                [
                    'nama' => 'Pamulang Barat',
                    'latitude' => -6.339500,
                    'longitude' => 106.736500,
                ],
                [
                    'nama' => 'Pamulang Timur',
                    'latitude' => -6.342500,
                    'longitude' => 106.757500,
                ],
                [
                    'nama' => 'Pondok Benda',
                    'latitude' => -6.344500, // UPDATE: Tukar ke posisi yang benar
                    'longitude' => 106.726500,
                ],
                [
                    'nama' => 'Pondok Cabe Ilir',
                    'latitude' => -6.333190,
                    'longitude' => 106.766860,
                ],
                [
                    'nama' => 'Pondok Cabe Udik',
                    'latitude' => -6.351220,
                    'longitude' => 106.760790,
                ],
            ],

            // =====================================================
            // SETU - 6 KELURAHAN
            // =====================================================
            'Setu' => [
                [
                    'nama' => 'Babakan',
                    'latitude' => -6.358500,
                    'longitude' => 106.674500,
                ],
                [
                    'nama' => 'Bakti Jaya',
                    'latitude' => -6.335500,
                    'longitude' => 106.686500,
                ],
                [
                    'nama' => 'Kademangan',
                    'latitude' => -6.337500,
                    'longitude' => 106.690500,
                ],
                [
                    'nama' => 'Keranggan',
                    'latitude' => -6.348500,
                    'longitude' => 106.671500,
                ],
                [
                    'nama' => 'Muncul',
                    'latitude' => -6.353500,
                    'longitude' => 106.683500,
                ],
                [
                    'nama' => 'Setu',
                    'latitude' => -6.347500, // UPDATE: Geser dari Pamulang ke Kelurahan Setu asli
                    'longitude' => 106.678719,
                ],
            ],

            // =====================================================
            // SERPONG - 9 KELURAHAN
            // =====================================================
            'Serpong' => [
                [
                    'nama' => 'Buaran',
                    'latitude' => -6.323500,
                    'longitude' => 106.669500,
                ],
                [
                    'nama' => 'Ciater',
                    'latitude' => -6.310500,
                    'longitude' => 106.687500,
                ],
                [
                    'nama' => 'Cilenggang',
                    'latitude' => -6.305500,
                    'longitude' => 106.670500,
                ],
                [
                    'nama' => 'Lengkong Gudang',
                    'latitude' => -6.297500,
                    'longitude' => 106.689500,
                ],
                [
                    'nama' => 'Lengkong Gudang Timur',
                    'latitude' => -6.296500,
                    'longitude' => 106.700500,
                ],
                [
                    'nama' => 'Lengkong Wetan',
                    'latitude' => -6.291500,
                    'longitude' => 106.683500,
                ],
                [
                    'nama' => 'Rawabuntu',
                    'latitude' => -6.310500,
                    'longitude' => 106.666500,
                ],
                [
                    'nama' => 'Rawamekar Jaya',
                    'latitude' => -6.303500,
                    'longitude' => 106.679500,
                ],
                [
                    'nama' => 'Serpong',
                    'latitude' => -6.318500,
                    'longitude' => 106.665500,
                ],
            ],

            // =====================================================
            // SERPONG UTARA - 7 KELURAHAN
            // =====================================================
            'Serpong Utara' => [
                [
                    'nama' => 'Jelupang',
                    'latitude' => -6.273500,
                    'longitude' => 106.680500,
                ],
                [
                    'nama' => 'Lengkong Karya',
                    'latitude' => -6.285500,
                    'longitude' => 106.685500,
                ],
                [
                    'nama' => 'Pakualam',
                    'latitude' => -6.269500,
                    'longitude' => 106.696500,
                ],
                [
                    'nama' => 'Pakulonan',
                    'latitude' => -6.255500,
                    'longitude' => 106.691500,
                ],
                [
                    'nama' => 'Pakujaya',
                    'latitude' => -6.259500,
                    'longitude' => 106.704500,
                ],
                [
                    'nama' => 'Pondok Jagung',
                    'latitude' => -6.260817, // UPDATE: Tukar ke koordinat pusat yang benar
                    'longitude' => 106.652840,
                ],
                [
                    'nama' => 'Pondok Jagung Timur',
                    'latitude' => -6.247127, // UPDATE: Tukar ke koordinat pusat yang benar
                    'longitude' => 106.667350,
                ],
            ],

            // =====================================================
            // PONDOK AREN - 11 KELURAHAN
            // =====================================================
            'Pondok Aren' => [
                [
                    'nama' => 'Jurangmangu Barat',
                    'latitude' => -6.278500,
                    'longitude' => 106.721500,
                ],
                [
                    'nama' => 'Jurangmangu Timur',
                    'latitude' => -6.274500,
                    'longitude' => 106.737500,
                ],
                [
                    'nama' => 'Parigi Baru',
                    'latitude' => -6.269500,
                    'longitude' => 106.698500,
                ],
                [
                    'nama' => 'Parigi',
                    'latitude' => -6.275500, // UPDATE: Dikembalikan ke pusat Parigi Bintaro
                    'longitude' => 106.702500,
                ],
                [
                    'nama' => 'Pondok Aren',
                    'latitude' => -6.282500,
                    'longitude' => 106.711500,
                ],
                [
                    'nama' => 'Pondok Betung',
                    'latitude' => -6.255500,
                    'longitude' => 106.735500,
                ],
                [
                    'nama' => 'Pondok Jaya',
                    'latitude' => -6.286500,
                    'longitude' => 106.729500,
                ],
                [
                    'nama' => 'Pondok Kacang Barat',
                    'latitude' => -6.256500, // UPDATE: Reposisi total ke area batas Ciledug
                    'longitude' => 106.698500,
                ],
                [
                    'nama' => 'Pondok Kacang Timur',
                    'latitude' => -6.265500, // UPDATE: Reposisi total ke area Pondok Kacang asli
                    'longitude' => 106.708500,
                ],
                [
                    'nama' => 'Pondok Karya',
                    'latitude' => -6.292500,
                    'longitude' => 106.735500,
                ],
                [
                    'nama' => 'Pondok Pucung',
                    'latitude' => -6.272800,
                    'longitude' => 106.708600,
                ],
            ],
        ];

        foreach ($data as $namaKecamatan => $kelurahans) {

            $kecamatan = Kecamatan::where(
                'nama',
                $namaKecamatan
            )->first();

            if (!$kecamatan) {
                continue;
            }

            foreach ($kelurahans as $kelurahan) {

                Kelurahan::updateOrCreate(
                    [
                        'kecamatan_id' => $kecamatan->id,
                        'nama' => $kelurahan['nama'],
                    ],
                    [
                        'latitude' => $kelurahan['latitude'],
                        'longitude' => $kelurahan['longitude'],
                        'keterangan' => 'Kelurahan ' . $kelurahan['nama'],
                    ]
                );

            }
        }
    }
}