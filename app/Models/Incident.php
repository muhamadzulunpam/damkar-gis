<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'kecamatan_id',
        'kelurahan_id',
        'jenis_kejadian_id',
        'kategori_kejadian_id',
        'user_id',
        'tanggal_kejadian',
        'waktu_kejadian',
        'alamat',
        'latitude',
        'longitude',
        'deskripsi',
        'jumlah_korban',
        'jumlah_meninggal',
        'jumlah_luka',
        'estimasi_kerugian',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'date',
        'waktu_kejadian' => 'datetime:H:i',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'estimasi_kerugian' => 'decimal:2',
    ];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function jenisKejadian()
    {
        return $this->belongsTo(JenisKejadian::class);
    }

    public function kategoriKejadian()
    {
        return $this->belongsTo(KategoriKejadian::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}