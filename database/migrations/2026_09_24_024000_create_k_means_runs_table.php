<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kmeans_runs', function (Blueprint $table) {
            $table->id();

            // 'penyelamatan' atau 'kebakaran'
            $table->string('jenis_kejadian', 100);

            // tahun = tahun akhir (dipertahankan agar modul lain tetap jalan)
            $table->unsignedSmallInteger('tahun');
            $table->unsignedSmallInteger('tahun_awal');
            $table->unsignedSmallInteger('tahun_akhir');

            $table->unsignedTinyInteger('jumlah_cluster')->default(3);

            // kualitas hasil
            $table->decimal('silhouette', 6, 3)->nullable();
            $table->decimal('davies_bouldin', 6, 3)->nullable();
            $table->json('evaluasi_k')->nullable();

            $table->foreignId('diproses_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['jenis_kejadian', 'tahun_awal', 'tahun_akhir']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kmeans_runs');
    }
};