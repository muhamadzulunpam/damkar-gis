<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function kejadianKebakarans()
    {
        return $this->hasMany(KejadianKebakaran::class);
    }

    public function kejadianPenyelamatans()
    {
        return $this->hasMany(KejadianPenyelamatan::class);
    }
}