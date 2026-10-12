<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();

            // Relasi wilayah
            $table->foreignId('kecamatan_id')
                ->constrained('kecamatans')
                ->restrictOnDelete();

            $table->foreignId('kelurahan_id')
                ->constrained('kelurahans')
                ->restrictOnDelete();

            // Relasi jenis dan kategori
            $table->foreignId('jenis_kejadian_id')
                ->constrained('jenis_kejadian')
                ->restrictOnDelete();

            $table->foreignId('kategori_kejadian_id')
                ->constrained('kategori_kejadian')
                ->restrictOnDelete();

            // Petugas yang mencatat incident
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Waktu kejadian
            $table->date('tanggal_kejadian');

            $table->time('waktu_kejadian');

            // Lokasi
            $table->string('alamat', 255);

            $table->decimal('latitude', 10, 7);

            $table->decimal('longitude', 10, 7);

            // Informasi kejadian
            $table->text('deskripsi')->nullable();

            $table->unsignedInteger('jumlah_korban')
                ->default(0);

            $table->unsignedInteger('jumlah_meninggal')
                ->default(0);

            $table->unsignedInteger('jumlah_luka')
                ->default(0);

            $table->decimal('estimasi_kerugian', 15, 2)
                ->default(0);

            // Status incident
            $table->string('status', 30)
                ->default('selesai');

            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Untuk pencarian berdasarkan tanggal
            $table->index('tanggal_kejadian');

            // Untuk analisis wilayah
            $table->index([
                'kecamatan_id',
                'kelurahan_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
