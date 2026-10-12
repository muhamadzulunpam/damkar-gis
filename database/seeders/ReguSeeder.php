<?php

namespace Database\Seeders;

use App\Models\Regu;
use Illuminate\Database\Seeder;

class ReguSeeder extends Seeder
{
    public function run(): void
    {
        $regus = [
            [
                'nama' => 'Alpha',
                'keterangan' => 'Regu Alpha',
                'aktif' => true,
            ],
            [
                'nama' => 'Bravo',
                'keterangan' => 'Regu Bravo',
                'aktif' => true,
            ],
            [
                'nama' => 'Charlie',
                'keterangan' => 'Regu Charlie',
                'aktif' => true,
            ],
        ];

        foreach ($regus as $regu) {
            Regu::updateOrCreate(
                ['nama' => $regu['nama']],
                $regu
            );
        }
    }
}