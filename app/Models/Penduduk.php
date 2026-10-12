<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penduduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelurahan_id',
        'tahun',
        'jumlah_penduduk',
        'luas_wilayah_km2',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'jumlah_penduduk' => 'integer',
        'luas_wilayah_km2' => 'decimal:4',
    ];

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    /**
     * Kepadatan penduduk per km².
     */
    public function getKepadatanPendudukAttribute(): float
    {
        if ((float) $this->luas_wilayah_km2 <= 0) {
            return 0;
        }

        return $this->jumlah_penduduk / (float) $this->luas_wilayah_km2;
    }
}