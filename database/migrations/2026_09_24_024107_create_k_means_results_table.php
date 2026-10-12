<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kmeans_results', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Proses K-Means
            |--------------------------------------------------------------------------
            */

            $table->foreignId('kmeans_run_id')
                ->constrained('kmeans_runs')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Wilayah
            |--------------------------------------------------------------------------
            */

            $table->foreignId('kelurahan_id')
                ->constrained('kelurahans')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Variabel input K-Means
            |--------------------------------------------------------------------------
            | jumlah_kejadian    = frekuensi kejadian
            | kepadatan_penduduk = rata-rata penduduk / luas wilayah (jiwa/km²)
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('jumlah_kejadian')->default(0);

            $table->decimal('kepadatan_penduduk', 12, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Informasi pendukung (bukan input K-Means)
            |--------------------------------------------------------------------------
            */

            $table->unsignedInteger('jumlah_korban')->default(0);

            $table->decimal('total_kerugian', 18, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Hasil K-Means
            |--------------------------------------------------------------------------
            */

            $table->unsignedTinyInteger('cluster');

            $table->string('tingkat_kerawanan', 20);

            $table->string('warna', 20);

            $table->decimal('silhouette_kelurahan', 6, 3)->nullable();

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Satu kelurahan hanya satu hasil
            | dalam satu proses K-Means
            |--------------------------------------------------------------------------
            */

            $table->unique([
                'kmeans_run_id',
                'kelurahan_id'
            ]);

            $table->index('cluster');
            $table->index('tingkat_kerawanan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kmeans_results');
    }
};