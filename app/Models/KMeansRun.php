<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KMeansRun extends Model
{
    protected $table = 'kmeans_runs';
    
    protected $fillable = [
        'jenis_kejadian',
        'tahun',
        'tahun_awal',
        'tahun_akhir',
        'jumlah_cluster',
        'diproses_oleh',
        'silhouette',
        'davies_bouldin',
        'evaluasi_k',
    ];

    protected $casts = [
        'evaluasi_k' => 'array',
    ];

    public function getRentangAttribute(): string
    {
        return $this->tahun_awal == $this->tahun_akhir
            ? (string) $this->tahun_awal
            : "{$this->tahun_awal}-{$this->tahun_akhir}";
    }

    /*
    |--------------------------------------------------------------------------
    | User yang menjalankan K-Means
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'diproses_oleh'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Hasil K-Means
    |--------------------------------------------------------------------------
    */

    public function results(): HasMany
    {
        return $this->hasMany(
            KMeansResult::class,
            'kmeans_run_id'
        );
    }
    
    public static function opsiPeriode($tahunList): array
    {
        $tahun = collect($tahunList)
            ->map(fn ($t) => (int) $t)
            ->unique()
            ->sort()
            ->values();

        if ($tahun->isEmpty()) {
            return [];
        }

        $opsi = [];

        for ($i = 0; $i < $tahun->count() - 1; $i++) {

            $awal  = $tahun[$i];
            $akhir = $tahun[$i + 1];

            $opsi["{$awal}/{$akhir}"] = [
                'value' => "{$awal}/{$akhir}",
                'label' => "{$awal}/{$akhir} (" . ($akhir - $awal + 1) . ' tahun)',
            ];
        }

        $min = $tahun->first();
        $max = $tahun->last();

        $opsi["{$min}/{$max}"] = [
            'value' => "{$min}/{$max}",
            'label' => "{$min}/{$max} (semua)",
        ];

        return array_values($opsi);
    }
}