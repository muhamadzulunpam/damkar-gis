<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kejadian_penyelamatans', function (Blueprint $table) {
            $table->id();

            // ID / kode penyelamatan
            $table->string('id_penyelamatan', 50)->unique();

            // Wilayah
            $table->foreignId('kecamatan_id')
                ->constrained('kecamatans')
                ->restrictOnDelete();

            $table->foreignId('kelurahan_id')
                ->constrained('kelurahans')
                ->restrictOnDelete();

            // Lokasi
            $table->string('alamat_lengkap', 255);

            // Pemilik / pelapor
            $table->string('nama_pemilik', 150)->nullable();

            // Waktu kejadian
            $table->date('tanggal');
            $table->time('waktu_kejadian');
            $table->time('waktu_tiba')->nullable();
            $table->time('waktu_selesai')->nullable();

            // Durasi penanganan
            $table->unsignedInteger('durasi_menit')->nullable();

            // Jenis dan kategori
            $table->string('jenis_kejadian', 100);
            $table->string('kategori_jenis', 100);

            // Kelompok penyelamatan
            $table->string('kelompok_penyelamatan', 150)->nullable();

            // Penyebab
            $table->string('penyebab', 255)->nullable();

            // Luas area
            $table->decimal('luas_area_m2', 12, 2)->nullable();

            // Kerugian
            $table->decimal('kerugian_harta', 15, 2)->default(0);

            // Korban
            $table->unsignedInteger('korban_jiwa')->default(0);

            // Operasional
            $table->unsignedInteger('jumlah_unit')->default(0);

            $table->foreignId('regu_id')
                ->nullable()
                ->constrained('regus')
                ->nullOnDelete();

            // Keterangan
            $table->text('keterangan')->nullable();

            $table->timestamps();

            // Index
            $table->index('tanggal');
            $table->index('jenis_kejadian');
            $table->index('kategori_jenis');
            $table->index('kelompok_penyelamatan');

            $table->index([
                'kecamatan_id',
                'kelurahan_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kejadian_penyelamatans');
    }
};