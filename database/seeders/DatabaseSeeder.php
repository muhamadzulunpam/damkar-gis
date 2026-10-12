<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;



class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // ADMIN
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@damkar-gis.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // PETUGAS
        User::create([
            'name' => 'Petugas Damkar',
            'email' => 'petugas@damkar-gis.test',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);
        
        $this->call([
            WilayahSeeder::class,
            KecamatanSeeder::class,
            KelurahanSeeder::class,
            JenisKejadianSeeder::class,
            KategoriKejadianSeeder::class,
            IncidentSeeder::class,
            ReguSeeder::class,
            KejadianKebakaranSeeder::class,
            KejadianPenyelamatanSeeder::class,
            PendudukSeeder::class,
            KejadianKebakaranSeeder::class,
        ]);
    }
}
