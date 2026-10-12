<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Penduduk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PendudukSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Ciputat 2022
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Cipayung', 'tahun' => 2022, 'jumlah_penduduk' => 26632, 'luas_wilayah_km2' => 2.49],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Ciputat', 'tahun' => 2022, 'jumlah_penduduk' => 25054, 'luas_wilayah_km2' => 1.83],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Jombang', 'tahun' => 2022, 'jumlah_penduduk' => 43552, 'luas_wilayah_km2' => 3.57],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Sawah', 'tahun' => 2022, 'jumlah_penduduk' => 32510, 'luas_wilayah_km2' => 2.61],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Sawah Baru', 'tahun' => 2022, 'jumlah_penduduk' => 29376, 'luas_wilayah_km2' => 2.98],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Serua', 'tahun' => 2022, 'jumlah_penduduk' => 41545, 'luas_wilayah_km2' => 3.79],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Serua Indah', 'tahun' => 2022, 'jumlah_penduduk' => 21456, 'luas_wilayah_km2' => 2.04],
            // Ciputat Timur 2022
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Cempaka Putih', 'tahun' => 2022, 'jumlah_penduduk' => 26228, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Cireundeu', 'tahun' => 2022, 'jumlah_penduduk' => 26006, 'luas_wilayah_km2' => 3.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Pisangan', 'tahun' => 2022, 'jumlah_penduduk' => 36362, 'luas_wilayah_km2' => 4.05],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Pondok Ranji', 'tahun' => 2022, 'jumlah_penduduk' => 30098, 'luas_wilayah_km2' => 3.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Rempoa', 'tahun' => 2022, 'jumlah_penduduk' => 26242, 'luas_wilayah_km2' => 2.2],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Rengas', 'tahun' => 2022, 'jumlah_penduduk' => 24197, 'luas_wilayah_km2' => 1.8],
            // Pamulang 2022
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Bambu Apus', 'tahun' => 2022, 'jumlah_penduduk' => 29313, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Benda Baru', 'tahun' => 2022, 'jumlah_penduduk' => 43968, 'luas_wilayah_km2' => 2.88],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Kedaung', 'tahun' => 2022, 'jumlah_penduduk' => 51410, 'luas_wilayah_km2' => 2.79],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pamulang Barat', 'tahun' => 2022, 'jumlah_penduduk' => 53430, 'luas_wilayah_km2' => 4.44],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pamulang Timur', 'tahun' => 2022, 'jumlah_penduduk' => 31617, 'luas_wilayah_km2' => 2.81],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Benda', 'tahun' => 2022, 'jumlah_penduduk' => 53984, 'luas_wilayah_km2' => 4.13],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Cabe Ilir', 'tahun' => 2022, 'jumlah_penduduk' => 36345, 'luas_wilayah_km2' => 4.21],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Cabe Udik', 'tahun' => 2022, 'jumlah_penduduk' => 21697, 'luas_wilayah_km2' => 6.14],
            // Pondok Aren 2022
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Jurangmangu Barat', 'tahun' => 2022, 'jumlah_penduduk' => 37470, 'luas_wilayah_km2' => 2.6],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Jurangmangu Timur', 'tahun' => 2022, 'jumlah_penduduk' => 30827, 'luas_wilayah_km2' => 2.66],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Parigi', 'tahun' => 2022, 'jumlah_penduduk' => 21361, 'luas_wilayah_km2' => 3.96],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Parigi Baru', 'tahun' => 2022, 'jumlah_penduduk' => 12701, 'luas_wilayah_km2' => 3.17],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Aren', 'tahun' => 2022, 'jumlah_penduduk' => 31539, 'luas_wilayah_km2' => 2.25],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Betung', 'tahun' => 2022, 'jumlah_penduduk' => 33371, 'luas_wilayah_km2' => 1.99],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Jaya', 'tahun' => 2022, 'jumlah_penduduk' => 10888, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Kacang Barat', 'tahun' => 2022, 'jumlah_penduduk' => 24069, 'luas_wilayah_km2' => 2.59],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Kacang Timur', 'tahun' => 2022, 'jumlah_penduduk' => 37620, 'luas_wilayah_km2' => 2.61],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Karya', 'tahun' => 2022, 'jumlah_penduduk' => 26521, 'luas_wilayah_km2' => 2.79],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Pucung', 'tahun' => 2022, 'jumlah_penduduk' => 27601, 'luas_wilayah_km2' => 3.01],
            // Serpong 2022
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Buaran', 'tahun' => 2022, 'jumlah_penduduk' => 19874, 'luas_wilayah_km2' => 3.8],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Ciater', 'tahun' => 2022, 'jumlah_penduduk' => 27271, 'luas_wilayah_km2' => 2.46],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Cilenggang', 'tahun' => 2022, 'jumlah_penduduk' => 10652, 'luas_wilayah_km2' => 1.47],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Gudang', 'tahun' => 2022, 'jumlah_penduduk' => 12097, 'luas_wilayah_km2' => 2.33],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Gudang Timur', 'tahun' => 2022, 'jumlah_penduduk' => 12296, 'luas_wilayah_km2' => 2.62],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Wetan', 'tahun' => 2022, 'jumlah_penduduk' => 10392, 'luas_wilayah_km2' => 2.51],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Rawabuntu', 'tahun' => 2022, 'jumlah_penduduk' => 28821, 'luas_wilayah_km2' => 3.72],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Rawamekar Jaya', 'tahun' => 2022, 'jumlah_penduduk' => 18550, 'luas_wilayah_km2' => 2.7],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Serpong', 'tahun' => 2022, 'jumlah_penduduk' => 21403, 'luas_wilayah_km2' => 1.99],
            // Serpong Utara 2022
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Jelupang', 'tahun' => 2022, 'jumlah_penduduk' => 33243, 'luas_wilayah_km2' => 3.5],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Lengkong Karya', 'tahun' => 2022, 'jumlah_penduduk' => 8179, 'luas_wilayah_km2' => 2.18],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakualam', 'tahun' => 2022, 'jumlah_penduduk' => 15831, 'luas_wilayah_km2' => 2.98],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakujaya', 'tahun' => 2022, 'jumlah_penduduk' => 27579, 'luas_wilayah_km2' => 7.43],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakulonan', 'tahun' => 2022, 'jumlah_penduduk' => 13050, 'luas_wilayah_km2' => 2.18],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pondok Jagung', 'tahun' => 2022, 'jumlah_penduduk' => 20429, 'luas_wilayah_km2' => 2.09],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pondok Jagung Timur', 'tahun' => 2022, 'jumlah_penduduk' => 19151, 'luas_wilayah_km2' => 2.1],
            // Setu 2022
            ['kecamatan' => 'Setu', 'kelurahan' => 'Babakan', 'tahun' => 2022, 'jumlah_penduduk' => 12439, 'luas_wilayah_km2' => 1.7],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Bakti Jaya', 'tahun' => 2022, 'jumlah_penduduk' => 19301, 'luas_wilayah_km2' => 1.74],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Kademangan', 'tahun' => 2022, 'jumlah_penduduk' => 23972, 'luas_wilayah_km2' => 2.06],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Keranggan', 'tahun' => 2022, 'jumlah_penduduk' => 8526, 'luas_wilayah_km2' => 2.05],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Muncul', 'tahun' => 2022, 'jumlah_penduduk' => 11138, 'luas_wilayah_km2' => 3.72],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Setu', 'tahun' => 2022, 'jumlah_penduduk' => 14831, 'luas_wilayah_km2' => 3.64],
            // Ciputat 2023
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Cipayung', 'tahun' => 2023, 'jumlah_penduduk' => 26841, 'luas_wilayah_km2' => 2.49],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Ciputat', 'tahun' => 2023, 'jumlah_penduduk' => 25148, 'luas_wilayah_km2' => 1.83],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Jombang', 'tahun' => 2023, 'jumlah_penduduk' => 44518, 'luas_wilayah_km2' => 3.57],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Sawah', 'tahun' => 2023, 'jumlah_penduduk' => 33152, 'luas_wilayah_km2' => 2.61],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Sawah Baru', 'tahun' => 2023, 'jumlah_penduduk' => 30126, 'luas_wilayah_km2' => 2.98],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Serua', 'tahun' => 2023, 'jumlah_penduduk' => 42254, 'luas_wilayah_km2' => 3.79],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Serua Indah', 'tahun' => 2023, 'jumlah_penduduk' => 22009, 'luas_wilayah_km2' => 2.04],
            // Ciputat Timur 2023
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Cempaka Putih', 'tahun' => 2023, 'jumlah_penduduk' => 26344, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Cireundeu', 'tahun' => 2023, 'jumlah_penduduk' => 26165, 'luas_wilayah_km2' => 3.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Pisangan', 'tahun' => 2023, 'jumlah_penduduk' => 36498, 'luas_wilayah_km2' => 4.05],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Pondok Ranji', 'tahun' => 2023, 'jumlah_penduduk' => 30640, 'luas_wilayah_km2' => 3.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Rempoa', 'tahun' => 2023, 'jumlah_penduduk' => 26183, 'luas_wilayah_km2' => 2.2],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Rengas', 'tahun' => 2023, 'jumlah_penduduk' => 24171, 'luas_wilayah_km2' => 1.8],
            // Pamulang 2023
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Bambu Apus', 'tahun' => 2023, 'jumlah_penduduk' => 29960, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Benda Baru', 'tahun' => 2023, 'jumlah_penduduk' => 44787, 'luas_wilayah_km2' => 2.88],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Kedaung', 'tahun' => 2023, 'jumlah_penduduk' => 51816, 'luas_wilayah_km2' => 2.79],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pamulang Barat', 'tahun' => 2023, 'jumlah_penduduk' => 53920, 'luas_wilayah_km2' => 4.44],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pamulang Timur', 'tahun' => 2023, 'jumlah_penduduk' => 32093, 'luas_wilayah_km2' => 2.81],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Benda', 'tahun' => 2023, 'jumlah_penduduk' => 55179, 'luas_wilayah_km2' => 4.13],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Cabe Ilir', 'tahun' => 2023, 'jumlah_penduduk' => 36700, 'luas_wilayah_km2' => 4.21],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Cabe Udik', 'tahun' => 2023, 'jumlah_penduduk' => 21841, 'luas_wilayah_km2' => 6.14],
            // Pondok Aren 2023
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Jurangmangu Barat', 'tahun' => 2023, 'jumlah_penduduk' => 37892, 'luas_wilayah_km2' => 2.6],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Jurangmangu Timur', 'tahun' => 2023, 'jumlah_penduduk' => 30801, 'luas_wilayah_km2' => 2.66],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Parigi', 'tahun' => 2023, 'jumlah_penduduk' => 21851, 'luas_wilayah_km2' => 3.96],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Parigi Baru', 'tahun' => 2023, 'jumlah_penduduk' => 12919, 'luas_wilayah_km2' => 3.17],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Aren', 'tahun' => 2023, 'jumlah_penduduk' => 32096, 'luas_wilayah_km2' => 2.25],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Betung', 'tahun' => 2023, 'jumlah_penduduk' => 33180, 'luas_wilayah_km2' => 1.99],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Jaya', 'tahun' => 2023, 'jumlah_penduduk' => 10998, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Kacang Barat', 'tahun' => 2023, 'jumlah_penduduk' => 24590, 'luas_wilayah_km2' => 2.59],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Kacang Timur', 'tahun' => 2023, 'jumlah_penduduk' => 38231, 'luas_wilayah_km2' => 2.61],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Karya', 'tahun' => 2023, 'jumlah_penduduk' => 26764, 'luas_wilayah_km2' => 2.79],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Pucung', 'tahun' => 2023, 'jumlah_penduduk' => 28078, 'luas_wilayah_km2' => 3.01],
            // Serpong 2023
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Buaran', 'tahun' => 2023, 'jumlah_penduduk' => 20605, 'luas_wilayah_km2' => 3.8],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Ciater', 'tahun' => 2023, 'jumlah_penduduk' => 28208, 'luas_wilayah_km2' => 2.46],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Cilenggang', 'tahun' => 2023, 'jumlah_penduduk' => 10896, 'luas_wilayah_km2' => 1.47],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Gudang', 'tahun' => 2023, 'jumlah_penduduk' => 12353, 'luas_wilayah_km2' => 2.33],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Gudang Timur', 'tahun' => 2023, 'jumlah_penduduk' => 12487, 'luas_wilayah_km2' => 2.62],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Wetan', 'tahun' => 2023, 'jumlah_penduduk' => 10594, 'luas_wilayah_km2' => 2.51],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Rawabuntu', 'tahun' => 2023, 'jumlah_penduduk' => 29185, 'luas_wilayah_km2' => 3.72],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Rawamekar Jaya', 'tahun' => 2023, 'jumlah_penduduk' => 18927, 'luas_wilayah_km2' => 2.7],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Serpong', 'tahun' => 2023, 'jumlah_penduduk' => 21745, 'luas_wilayah_km2' => 1.99],
            // Serpong Utara 2023
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Jelupang', 'tahun' => 2023, 'jumlah_penduduk' => 33743, 'luas_wilayah_km2' => 3.5],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Lengkong Karya', 'tahun' => 2023, 'jumlah_penduduk' => 8350, 'luas_wilayah_km2' => 2.18],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakualam', 'tahun' => 2023, 'jumlah_penduduk' => 16012, 'luas_wilayah_km2' => 2.98],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakujaya', 'tahun' => 2023, 'jumlah_penduduk' => 28041, 'luas_wilayah_km2' => 7.43],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakulonan', 'tahun' => 2023, 'jumlah_penduduk' => 13115, 'luas_wilayah_km2' => 2.18],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pondok Jagung', 'tahun' => 2023, 'jumlah_penduduk' => 20442, 'luas_wilayah_km2' => 2.09],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pondok Jagung Timur', 'tahun' => 2023, 'jumlah_penduduk' => 19512, 'luas_wilayah_km2' => 2.1],
            // Setu 2023
            ['kecamatan' => 'Setu', 'kelurahan' => 'Babakan', 'tahun' => 2023, 'jumlah_penduduk' => 12869, 'luas_wilayah_km2' => 1.7],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Bakti Jaya', 'tahun' => 2023, 'jumlah_penduduk' => 19838, 'luas_wilayah_km2' => 1.74],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Kademangan', 'tahun' => 2023, 'jumlah_penduduk' => 24563, 'luas_wilayah_km2' => 2.06],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Keranggan', 'tahun' => 2023, 'jumlah_penduduk' => 8824, 'luas_wilayah_km2' => 2.05],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Muncul', 'tahun' => 2023, 'jumlah_penduduk' => 11388, 'luas_wilayah_km2' => 3.72],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Setu', 'tahun' => 2023, 'jumlah_penduduk' => 15177, 'luas_wilayah_km2' => 3.64],
            // Ciputat 2024
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Cipayung', 'tahun' => 2024, 'jumlah_penduduk' => 27631, 'luas_wilayah_km2' => 2.49],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Ciputat', 'tahun' => 2024, 'jumlah_penduduk' => 25725, 'luas_wilayah_km2' => 1.83],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Jombang', 'tahun' => 2024, 'jumlah_penduduk' => 46266, 'luas_wilayah_km2' => 3.57],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Sawah', 'tahun' => 2024, 'jumlah_penduduk' => 34438, 'luas_wilayah_km2' => 2.61],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Sawah Baru', 'tahun' => 2024, 'jumlah_penduduk' => 31074, 'luas_wilayah_km2' => 2.98],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Serua', 'tahun' => 2024, 'jumlah_penduduk' => 43608, 'luas_wilayah_km2' => 3.79],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Serua Indah', 'tahun' => 2024, 'jumlah_penduduk' => 23031, 'luas_wilayah_km2' => 2.04],
            // Ciputat Timur 2024
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Cempaka Putih', 'tahun' => 2024, 'jumlah_penduduk' => 26983, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Cireundeu', 'tahun' => 2024, 'jumlah_penduduk' => 26867, 'luas_wilayah_km2' => 3.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Pisangan', 'tahun' => 2024, 'jumlah_penduduk' => 37459, 'luas_wilayah_km2' => 4.05],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Pondok Ranji', 'tahun' => 2024, 'jumlah_penduduk' => 31647, 'luas_wilayah_km2' => 3.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Rempoa', 'tahun' => 2024, 'jumlah_penduduk' => 26943, 'luas_wilayah_km2' => 2.2],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Rengas', 'tahun' => 2024, 'jumlah_penduduk' => 24899, 'luas_wilayah_km2' => 1.8],
            // Pamulang 2024
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Bambu Apus', 'tahun' => 2024, 'jumlah_penduduk' => 31298, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Benda Baru', 'tahun' => 2024, 'jumlah_penduduk' => 46266, 'luas_wilayah_km2' => 2.88],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Kedaung', 'tahun' => 2024, 'jumlah_penduduk' => 53061, 'luas_wilayah_km2' => 2.79],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pamulang Barat', 'tahun' => 2024, 'jumlah_penduduk' => 55293, 'luas_wilayah_km2' => 4.44],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pamulang Timur', 'tahun' => 2024, 'jumlah_penduduk' => 33069, 'luas_wilayah_km2' => 2.81],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Benda', 'tahun' => 2024, 'jumlah_penduduk' => 57293, 'luas_wilayah_km2' => 4.13],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Cabe Ilir', 'tahun' => 2024, 'jumlah_penduduk' => 37949, 'luas_wilayah_km2' => 4.21],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Cabe Udik', 'tahun' => 2024, 'jumlah_penduduk' => 22516, 'luas_wilayah_km2' => 6.14],
            // Pondok Aren 2024
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Jurangmangu Barat', 'tahun' => 2024, 'jumlah_penduduk' => 39381, 'luas_wilayah_km2' => 2.6],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Jurangmangu Timur', 'tahun' => 2024, 'jumlah_penduduk' => 32114, 'luas_wilayah_km2' => 2.66],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Parigi', 'tahun' => 2024, 'jumlah_penduduk' => 23061, 'luas_wilayah_km2' => 3.96],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Parigi Baru', 'tahun' => 2024, 'jumlah_penduduk' => 13341, 'luas_wilayah_km2' => 3.17],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Aren', 'tahun' => 2024, 'jumlah_penduduk' => 33664, 'luas_wilayah_km2' => 2.25],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Betung', 'tahun' => 2024, 'jumlah_penduduk' => 34118, 'luas_wilayah_km2' => 1.99],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Jaya', 'tahun' => 2024, 'jumlah_penduduk' => 11239, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Kacang Barat', 'tahun' => 2024, 'jumlah_penduduk' => 25769, 'luas_wilayah_km2' => 2.59],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Kacang Timur', 'tahun' => 2024, 'jumlah_penduduk' => 39322, 'luas_wilayah_km2' => 2.61],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Karya', 'tahun' => 2024, 'jumlah_penduduk' => 27806, 'luas_wilayah_km2' => 2.79],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Pucung', 'tahun' => 2024, 'jumlah_penduduk' => 29038, 'luas_wilayah_km2' => 3.01],
            // Serpong 2024
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Buaran', 'tahun' => 2024, 'jumlah_penduduk' => 21888, 'luas_wilayah_km2' => 3.8],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Ciater', 'tahun' => 2024, 'jumlah_penduduk' => 29551, 'luas_wilayah_km2' => 2.46],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Cilenggang', 'tahun' => 2024, 'jumlah_penduduk' => 11259, 'luas_wilayah_km2' => 1.47],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Gudang', 'tahun' => 2024, 'jumlah_penduduk' => 12701, 'luas_wilayah_km2' => 2.33],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Gudang Timur', 'tahun' => 2024, 'jumlah_penduduk' => 12965, 'luas_wilayah_km2' => 2.62],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Wetan', 'tahun' => 2024, 'jumlah_penduduk' => 10975, 'luas_wilayah_km2' => 2.51],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Rawabuntu', 'tahun' => 2024, 'jumlah_penduduk' => 29880, 'luas_wilayah_km2' => 3.72],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Rawamekar Jaya', 'tahun' => 2024, 'jumlah_penduduk' => 19564, 'luas_wilayah_km2' => 2.7],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Serpong', 'tahun' => 2024, 'jumlah_penduduk' => 22333, 'luas_wilayah_km2' => 1.99],
            // Serpong Utara 2024
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Jelupang', 'tahun' => 2024, 'jumlah_penduduk' => 34991, 'luas_wilayah_km2' => 3.5],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Lengkong Karya', 'tahun' => 2024, 'jumlah_penduduk' => 8672, 'luas_wilayah_km2' => 2.18],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakualam', 'tahun' => 2024, 'jumlah_penduduk' => 16568, 'luas_wilayah_km2' => 2.98],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakujaya', 'tahun' => 2024, 'jumlah_penduduk' => 29463, 'luas_wilayah_km2' => 7.43],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakulonan', 'tahun' => 2024, 'jumlah_penduduk' => 13238, 'luas_wilayah_km2' => 2.18],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pondok Jagung', 'tahun' => 2024, 'jumlah_penduduk' => 21121, 'luas_wilayah_km2' => 2.09],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pondok Jagung Timur', 'tahun' => 2024, 'jumlah_penduduk' => 20267, 'luas_wilayah_km2' => 2.1],
            // Setu 2024
            ['kecamatan' => 'Setu', 'kelurahan' => 'Babakan', 'tahun' => 2024, 'jumlah_penduduk' => 13512, 'luas_wilayah_km2' => 1.7],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Bakti Jaya', 'tahun' => 2024, 'jumlah_penduduk' => 20641, 'luas_wilayah_km2' => 1.74],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Kademangan', 'tahun' => 2024, 'jumlah_penduduk' => 25513, 'luas_wilayah_km2' => 2.06],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Keranggan', 'tahun' => 2024, 'jumlah_penduduk' => 9092, 'luas_wilayah_km2' => 2.05],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Muncul', 'tahun' => 2024, 'jumlah_penduduk' => 11722, 'luas_wilayah_km2' => 3.72],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Setu', 'tahun' => 2024, 'jumlah_penduduk' => 15522, 'luas_wilayah_km2' => 3.64],
            // Ciputat 2025
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Cipayung', 'tahun' => 2025, 'jumlah_penduduk' => 27930, 'luas_wilayah_km2' => 2.49],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Ciputat', 'tahun' => 2025, 'jumlah_penduduk' => 25863, 'luas_wilayah_km2' => 1.83],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Jombang', 'tahun' => 2025, 'jumlah_penduduk' => 47273, 'luas_wilayah_km2' => 3.57],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Sawah', 'tahun' => 2025, 'jumlah_penduduk' => 35018, 'luas_wilayah_km2' => 2.61],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Sawah Baru', 'tahun' => 2025, 'jumlah_penduduk' => 31643, 'luas_wilayah_km2' => 2.98],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Serua', 'tahun' => 2025, 'jumlah_penduduk' => 44304, 'luas_wilayah_km2' => 3.79],
            ['kecamatan' => 'Ciputat', 'kelurahan' => 'Serua Indah', 'tahun' => 2025, 'jumlah_penduduk' => 23587, 'luas_wilayah_km2' => 2.04],
            // Ciputat Timur 2025
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Cempaka Putih', 'tahun' => 2025, 'jumlah_penduduk' => 27193, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Cireundeu', 'tahun' => 2025, 'jumlah_penduduk' => 27056, 'luas_wilayah_km2' => 3.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Pisangan', 'tahun' => 2025, 'jumlah_penduduk' => 37874, 'luas_wilayah_km2' => 4.05],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Pondok Ranji', 'tahun' => 2025, 'jumlah_penduduk' => 32221, 'luas_wilayah_km2' => 3.4],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Rempoa', 'tahun' => 2025, 'jumlah_penduduk' => 27068, 'luas_wilayah_km2' => 2.2],
            ['kecamatan' => 'Ciputat Timur', 'kelurahan' => 'Rengas', 'tahun' => 2025, 'jumlah_penduduk' => 25110, 'luas_wilayah_km2' => 1.8],
            // Pamulang 2025
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Bambu Apus', 'tahun' => 2025, 'jumlah_penduduk' => 32006, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Benda Baru', 'tahun' => 2025, 'jumlah_penduduk' => 46957, 'luas_wilayah_km2' => 2.88],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Kedaung', 'tahun' => 2025, 'jumlah_penduduk' => 53386, 'luas_wilayah_km2' => 2.79],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pamulang Barat', 'tahun' => 2025, 'jumlah_penduduk' => 55861, 'luas_wilayah_km2' => 4.44],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pamulang Timur', 'tahun' => 2025, 'jumlah_penduduk' => 33427, 'luas_wilayah_km2' => 2.81],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Benda', 'tahun' => 2025, 'jumlah_penduduk' => 58403, 'luas_wilayah_km2' => 4.13],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Cabe Ilir', 'tahun' => 2025, 'jumlah_penduduk' => 38477, 'luas_wilayah_km2' => 4.21],
            ['kecamatan' => 'Pamulang', 'kelurahan' => 'Pondok Cabe Udik', 'tahun' => 2025, 'jumlah_penduduk' => 22769, 'luas_wilayah_km2' => 6.14],
            // Pondok Aren 2025
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Jurangmangu Barat', 'tahun' => 2025, 'jumlah_penduduk' => 39943, 'luas_wilayah_km2' => 2.6],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Jurangmangu Timur', 'tahun' => 2025, 'jumlah_penduduk' => 32477, 'luas_wilayah_km2' => 2.66],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Parigi', 'tahun' => 2025, 'jumlah_penduduk' => 23769, 'luas_wilayah_km2' => 3.96],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Parigi Baru', 'tahun' => 2025, 'jumlah_penduduk' => 13599, 'luas_wilayah_km2' => 3.17],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Aren', 'tahun' => 2025, 'jumlah_penduduk' => 34147, 'luas_wilayah_km2' => 2.25],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Betung', 'tahun' => 2025, 'jumlah_penduduk' => 34087, 'luas_wilayah_km2' => 1.99],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Jaya', 'tahun' => 2025, 'jumlah_penduduk' => 11475, 'luas_wilayah_km2' => 2.4],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Kacang Barat', 'tahun' => 2025, 'jumlah_penduduk' => 26358, 'luas_wilayah_km2' => 2.59],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Kacang Timur', 'tahun' => 2025, 'jumlah_penduduk' => 39795, 'luas_wilayah_km2' => 2.61],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Karya', 'tahun' => 2025, 'jumlah_penduduk' => 28194, 'luas_wilayah_km2' => 2.79],
            ['kecamatan' => 'Pondok Aren', 'kelurahan' => 'Pondok Pucung', 'tahun' => 2025, 'jumlah_penduduk' => 29431, 'luas_wilayah_km2' => 3.01],
            // Serpong 2025
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Buaran', 'tahun' => 2025, 'jumlah_penduduk' => 22452, 'luas_wilayah_km2' => 3.8],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Ciater', 'tahun' => 2025, 'jumlah_penduduk' => 30385, 'luas_wilayah_km2' => 2.46],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Cilenggang', 'tahun' => 2025, 'jumlah_penduduk' => 11448, 'luas_wilayah_km2' => 1.47],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Gudang', 'tahun' => 2025, 'jumlah_penduduk' => 12958, 'luas_wilayah_km2' => 2.33],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Gudang Timur', 'tahun' => 2025, 'jumlah_penduduk' => 13406, 'luas_wilayah_km2' => 2.62],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Lengkong Wetan', 'tahun' => 2025, 'jumlah_penduduk' => 11371, 'luas_wilayah_km2' => 2.51],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Rawabuntu', 'tahun' => 2025, 'jumlah_penduduk' => 30329, 'luas_wilayah_km2' => 3.72],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Rawamekar Jaya', 'tahun' => 2025, 'jumlah_penduduk' => 19929, 'luas_wilayah_km2' => 2.7],
            ['kecamatan' => 'Serpong', 'kelurahan' => 'Serpong', 'tahun' => 2025, 'jumlah_penduduk' => 22701, 'luas_wilayah_km2' => 1.99],
            // Serpong Utara 2025
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Jelupang', 'tahun' => 2025, 'jumlah_penduduk' => 35459, 'luas_wilayah_km2' => 3.5],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Lengkong Karya', 'tahun' => 2025, 'jumlah_penduduk' => 8886, 'luas_wilayah_km2' => 2.18],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakualam', 'tahun' => 2025, 'jumlah_penduduk' => 16743, 'luas_wilayah_km2' => 2.98],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakujaya', 'tahun' => 2025, 'jumlah_penduduk' => 30146, 'luas_wilayah_km2' => 7.43],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pakulonan', 'tahun' => 2025, 'jumlah_penduduk' => 13225, 'luas_wilayah_km2' => 2.18],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pondok Jagung', 'tahun' => 2025, 'jumlah_penduduk' => 21283, 'luas_wilayah_km2' => 2.09],
            ['kecamatan' => 'Serpong Utara', 'kelurahan' => 'Pondok Jagung Timur', 'tahun' => 2025, 'jumlah_penduduk' => 20563, 'luas_wilayah_km2' => 2.1],
            // Setu 2025
            ['kecamatan' => 'Setu', 'kelurahan' => 'Babakan', 'tahun' => 2025, 'jumlah_penduduk' => 13892, 'luas_wilayah_km2' => 1.7],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Bakti Jaya', 'tahun' => 2025, 'jumlah_penduduk' => 21068, 'luas_wilayah_km2' => 1.74],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Kademangan', 'tahun' => 2025, 'jumlah_penduduk' => 26056, 'luas_wilayah_km2' => 2.06],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Keranggan', 'tahun' => 2025, 'jumlah_penduduk' => 9361, 'luas_wilayah_km2' => 2.05],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Muncul', 'tahun' => 2025, 'jumlah_penduduk' => 12091, 'luas_wilayah_km2' => 3.72],
            ['kecamatan' => 'Setu', 'kelurahan' => 'Setu', 'tahun' => 2025, 'jumlah_penduduk' => 15665, 'luas_wilayah_km2' => 3.64],
        ];

        DB::transaction(function () use ($data) {
            foreach ($data as $row) {
                $kecamatan = Kecamatan::where('nama', $row['kecamatan'])->first();
                if (!$kecamatan) {
                    throw new \RuntimeException("Kecamatan tidak ditemukan: {$row['kecamatan']}");
                }

                $kelurahan = Kelurahan::where('nama', $row['kelurahan'])
                    ->where('kecamatan_id', $kecamatan->id)
                    ->first();

                if (!$kelurahan) {
                    throw new \RuntimeException(
                        "Kelurahan tidak ditemukan: {$row['kelurahan']} ({$row['kecamatan']})"
                    );
                }

                Penduduk::updateOrCreate(
                    [
                        'kelurahan_id' => $kelurahan->id,
                        'tahun' => $row['tahun'],
                    ],
                    [
                        'jumlah_penduduk' => $row['jumlah_penduduk'],
                        'luas_wilayah_km2' => $row['luas_wilayah_km2'],
                    ]
                );
            }
        });
    }
}