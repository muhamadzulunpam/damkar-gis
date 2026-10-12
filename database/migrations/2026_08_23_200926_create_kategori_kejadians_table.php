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
        Schema::create('kategori_kejadian', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jenis_kejadian_id')
                ->constrained('jenis_kejadian')
                ->restrictOnDelete();

            $table->string('nama', 100);

            $table->text('keterangan')->nullable();

            $table->boolean('aktif')->default(true);

            $table->timestamps();

            $table->unique([
                'jenis_kejadian_id',
                'nama'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_kejadian');
    }
};
