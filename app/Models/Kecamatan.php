<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    /** @use HasFactory<\Database\Factories\KecamatanFactory> */
    use HasFactory;
    protected $fillable = [
        'nama',
        'keterangan',
    ];

    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class);
    }
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function kejadianKebakarans()
    {
        return $this->hasMany(KejadianKebakaran::class);
    }
}
