<?php

namespace Database\Seeders;

use App\Models\Incident;
use App\Models\User;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\JenisKejadian;
use App\Models\KategoriKejadian;
use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        $petugas = User::where('role', 'petugas')->first();

        if (!$petugas) {
            return;
        }

        $data = [
            [
                'kecamatan' => 'Pamulang',
                'kelurahan' => 'Pamulang Barat',
                'jenis' => 'Kebakaran',
                'kategori' => 'Rumah',
                'tanggal' => '2026-08-01',
                'waktu' => '14:30',
                'alamat' => 'Pamulang Barat, Tangerang Selatan',
                'latitude' => -6.3425000,
                'longitude' => 106.7385000,
                'korban' => 2,
                'meninggal' => 0,
                'luka' => 2,
                'kerugian' => 150000000,
                'deskripsi' => 'Kebakaran rumah warga.',
            ],

            [
                'kecamatan' => 'Pamulang',
                'kelurahan' => 'Benda Baru',
                'jenis' => 'Kebakaran',
                'kategori' => 'Kendaraan',
                'tanggal' => '2026-08-03',
                'waktu' => '19:15',
                'alamat' => 'Benda Baru, Tangerang Selatan',
                'latitude' => -6.3315000,
                'longitude' => 106.7455000,
                'korban' => 0,
                'meninggal' => 0,
                'luka' => 0,
                'kerugian' => 75000000,
                'deskripsi' => 'Kebakaran kendaraan.',
            ],

            [
                'kecamatan' => 'Ciputat',
                'kelurahan' => 'Ciputat',
                'jenis' => 'Bencana Alam',
                'kategori' => 'Banjir',
                'tanggal' => '2026-08-05',
                'waktu' => '08:00',
                'alamat' => 'Ciputat, Tangerang Selatan',
                'latitude' => -6.3205000,
                'longitude' => 106.7205000,
                'korban' => 5,
                'meninggal' => 0,
                'luka' => 1,
                'kerugian' => 250000000,
                'deskripsi' => 'Banjir akibat hujan deras.',
            ],

            [
                'kecamatan' => 'Serpong',
                'kelurahan' => 'Serpong',
                'jenis' => 'Kebakaran',
                'kategori' => 'Gedung/Bangunan',
                'tanggal' => '2026-08-07',
                'waktu' => '21:00',
                'alamat' => 'Serpong, Tangerang Selatan',
                'latitude' => -6.3105000,
                'longitude' => 106.6655000,
                'korban' => 8,
                'meninggal' => 1,
                'luka' => 3,
                'kerugian' => 800000000,
                'deskripsi' => 'Kebakaran bangunan.',
            ],
        ];

        foreach ($data as $item) {

            $kecamatan = Kecamatan::where(
                'nama',
                $item['kecamatan']
            )->first();

            if (!$kecamatan) {
                continue;
            }

            $kelurahan = Kelurahan::where(
                'nama',
                $item['kelurahan']
            )
                ->where(
                    'kecamatan_id',
                    $kecamatan->id
                )
                ->first();

            $jenis = JenisKejadian::where(
                'nama',
                $item['jenis']
            )->first();

            if (!$kelurahan || !$jenis) {
                continue;
            }

            $kategori = KategoriKejadian::where(
                'nama',
                $item['kategori']
            )
                ->where(
                    'jenis_kejadian_id',
                    $jenis->id
                )
                ->first();

            if (!$kategori) {
                continue;
            }

            Incident::create([
                'kecamatan_id' => $kecamatan->id,
                'kelurahan_id' => $kelurahan->id,
                'jenis_kejadian_id' => $jenis->id,
                'kategori_kejadian_id' => $kategori->id,
                'user_id' => $petugas->id,
                'tanggal_kejadian' => $item['tanggal'],
                'waktu_kejadian' => $item['waktu'],
                'alamat' => $item['alamat'],
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
                'jumlah_korban' => $item['korban'],
                'jumlah_meninggal' => $item['meninggal'],
                'jumlah_luka' => $item['luka'],
                'estimasi_kerugian' => $item['kerugian'],
                'deskripsi' => $item['deskripsi'],
                'status' => 'selesai',
            ]);
        }
    }
}