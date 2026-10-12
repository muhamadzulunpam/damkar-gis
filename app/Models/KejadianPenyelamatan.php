<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KejadianPenyelamatan extends Model
{
    use HasFactory;

    protected $table = 'kejadian_penyelamatans';

    protected $fillable = [
        'id_penyelamatan',
        'tanggal',
        'kecamatan_id',
        'kelurahan_id',
        'alamat_lengkap',
        'nama_pemilik',
        'waktu_kejadian',
        'waktu_tiba',
        'waktu_selesai',
        'durasi_menit',
        'jenis_kejadian',
        'kategori_jenis',
        'kelompok_penyelamatan',
        'penyebab',
        'luas_area_m2',
        'kerugian_harta',
        'korban_jiwa',
        'jumlah_unit',
        'regu_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'luas_area_m2' => 'decimal:2',
        'kerugian_harta' => 'decimal:2',
    ];

    /**
     * Relasi ke Kecamatan
     */
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    /**
     * Relasi ke Kelurahan
     */
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    /**
     * Generate ID, contoh: PY-2026-0001
     */
    public static function generateIdPenyelamatan($tanggal = null): string
    {
        $year = $tanggal
            ? Carbon::parse($tanggal)->format('Y')
            : now()->format('Y');

        $prefix = 'PY-' . $year . '-';

        $lastRecord = self::where('id_penyelamatan', 'like', $prefix . '%')
            ->orderBy('id_penyelamatan', 'desc')
            ->first();
        if ($lastRecord) {
            $lastNumber = (int) substr($lastRecord->id_penyelamatan, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Hitung durasi (menit) dari waktu kejadian sampai waktu tiba.
     */
    public static function hitungDurasiMenit($waktuKejadian, $waktuTiba): ?int
    {
        if (empty($waktuKejadian) || empty($waktuTiba)) {
            return null;
        }

        try {
            $kejadian = Carbon::createFromFormat('H:i', $waktuKejadian);
            $tiba = Carbon::createFromFormat('H:i', $waktuTiba);

            // Lewat tengah malam (contoh: kejadian 23:30, tiba 00:15)
            if ($tiba->lessThan($kejadian)) {
                $tiba->addDay();
            }

            return (int) round($kejadian->diffInMinutes($tiba));
        } catch (\Exception $e) {
            return null;
        }
    }
    public function regu()
    {
        return $this->belongsTo(Regu::class);
    }
}