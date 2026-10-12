<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKejadian extends Model
{
    /** @use HasFactory<\Database\Factories\JenisKejadianFactory> */
    use HasFactory;
    protected $table = 'jenis_kejadian';
    protected $fillable = [
        'nama',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function kategoris()
    {
        return $this->hasMany(KategoriKejadian::class);
    }
    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }
}
