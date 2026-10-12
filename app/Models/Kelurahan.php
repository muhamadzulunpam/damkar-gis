<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelurahan extends Model
{
    /** @use HasFactory<\Database\Factories\KelurahanFactory> */
    use HasFactory;
    
    protected $fillable = [
        'kecamatan_id',
        'nama',
        'keterangan',
        'latitude',
        'longitude',
    ];

    /**
     * Kelurahan dimiliki oleh satu Kecamatan.
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    
    public function kejadianKebakarans()
    {
        return $this->hasMany(
            KejadianKebakaran::class
        );
    }

    public function kejadianPenyelamatans()
    {
        return $this->hasMany(
            KejadianPenyelamatan::class
        );
    }

    public function kmeansResults(): HasMany
    {
        return $this->hasMany(
            KMeansResult::class
        );
    }

    public function penduduks(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }
    
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

}

