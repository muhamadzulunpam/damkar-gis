<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKejadian extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriKejadianFactory> */
    use HasFactory;
    protected $table = 'kategori_kejadian';
    protected $fillable = [
        'jenis_kejadian_id',
        'nama',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    /**
     * Kategori dimiliki oleh satu jenis kejadian.
     */
    public function jenisKejadian()
    {
        return $this->belongsTo(
            JenisKejadian::class
        );
    }
}
