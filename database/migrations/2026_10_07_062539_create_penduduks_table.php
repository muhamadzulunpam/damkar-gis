<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kelurahan_id')
                ->constrained('kelurahans')
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('tahun');

            $table->unsignedInteger('jumlah_penduduk');

            $table->decimal('luas_wilayah_km2', 12, 4);

            $table->timestamps();

            $table->unique(['kelurahan_id', 'tahun']);

            $table->index('tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduks');
    }
};