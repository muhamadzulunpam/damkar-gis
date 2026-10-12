<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KMeansResult extends Model
{
    protected $table = 'kmeans_results';

    protected $fillable = [
        'kmeans_run_id',
        'kelurahan_id',
        'jumlah_kejadian',
        'jumlah_korban',
        'total_kerugian',
        'kepadatan_penduduk',
        'silhouette_kelurahan',
        'cluster',
        'tingkat_kerawanan',
        'warna',
    ];

    protected $casts = [
        'jumlah_kejadian' => 'integer',
        'jumlah_korban' => 'integer',
        'total_kerugian' => 'decimal:2',
        'kepadatan_penduduk' => 'decimal:2',
        'silhouette_kelurahan' => 'decimal:3',
        'cluster' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Proses K-Means
    |--------------------------------------------------------------------------
    */

    public function kmeansRun(): BelongsTo
    {
        return $this->belongsTo(
            KMeansRun::class,
            'kmeans_run_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Kelurahan
    |--------------------------------------------------------------------------
    */

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(
            Kelurahan::class
        );
    }
}