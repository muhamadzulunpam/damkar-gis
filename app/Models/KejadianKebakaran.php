<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;   
use App\Models\Regu;

class KejadianKebakaran extends Model
{
    use HasFactory;

    protected $table = 'kejadian_kebakarans';

    protected $fillable = [
        'id_kejadian',
        'kecamatan_id',
        'kelurahan_id',
        'alamat_lengkap',
        'nama_pemilik',
        'tanggal',
        'waktu_kejadian',
        'waktu_tiba',
        'waktu_selesai',
        'durasi_menit',
        'jenis_kejadian',
        'kategori_jenis',
        'kelompok_kebakaran',
        'penyebab',
        'luas_area_m2',
        'kerugian_harta',
        'kerugian_benda',
        'korban_jiwa',
        'jumlah_unit',
        'regu_id',
        'keterangan_korban',
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
     public static function generateIdKejadian($tanggal = null)
    {
        // Ambil tahun dari tanggal kejadian atau tahun sekarang
        $year = $tanggal
            ? Carbon::parse($tanggal)->format('Y')
            : now()->format('Y');

        $prefix = 'KB-' . $year . '-';

        // Cari record terakhir dengan prefix tahun yang sama
        $lastRecord = self::where('id_kejadian', 'like', $prefix . '%')
            ->orderBy('id_kejadian', 'desc')
            ->first();

        if ($lastRecord) {
            // Ambil nomor urut terakhir (4 digit terakhir), tambah 1
            $lastNumber = (int) substr($lastRecord->id_kejadian, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format dengan leading zero (4 digit)
        $number = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return $prefix . $number;
    }
    public static function hitungDurasiMenit($waktuKejadian, $waktuTiba)
    {
        // Jika salah satu kosong, return null
        if (empty($waktuKejadian) || empty($waktuTiba)) {
            return null;
        }

        try {
            $kejadian = Carbon::createFromFormat('H:i', $waktuKejadian);
            $tiba = Carbon::createFromFormat('H:i', $waktuTiba);

            // Jika waktu tiba < waktu kejadian, berarti lewat tengah malam
            // Contoh: kejadian 23:30, tiba 00:15 (besoknya)
            if ($tiba->lessThan($kejadian)) {
                $tiba->addDay();
            }

            return $kejadian->diffInMinutes($tiba);
        } catch (\Exception $e) {
            return null;
        }
    }
    public function regu()
    {
        return $this->belongsTo(Regu::class);
    }
}