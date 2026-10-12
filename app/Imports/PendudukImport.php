<?php

namespace App\Imports;

use App\Models\Kelurahan;
use App\Models\Penduduk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PendudukImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];
    public int $imported = 0;

    public function __construct(protected int $tahun)
    {
    }

    public function collection(Collection $rows): void
    {
        $kelurahanIds = Kelurahan::pluck('id')->all();
        $valid = [];

        foreach ($rows as $index => $row) {
            $baris = $index + 2; // baris 1 = heading

            // Lewati baris yang benar-benar kosong
            if ($row->filter(fn ($v) => $v !== null && $v !== '')->isEmpty()) {
                continue;
            }

            $data = [
                'kelurahan_id'     => $row['kelurahan_id'] ?? null,
                'tahun'            => $row['tahun'] ?? null,
                'jumlah_penduduk'  => $row['jumlah_penduduk'] ?? null,
                'luas_wilayah_km2' => is_string($row['luas_wilayah_km2'] ?? null)
                    ? str_replace(',', '.', $row['luas_wilayah_km2'])
                    : ($row['luas_wilayah_km2'] ?? null),
            ];

            $validator = Validator::make($data, [
                'kelurahan_id'     => ['required', 'integer', 'in:' . implode(',', $kelurahanIds)],
                'tahun'            => ['required', 'integer', 'in:' . $this->tahun],
                'jumlah_penduduk'  => ['required', 'integer', 'min:0'],
                'luas_wilayah_km2' => ['required', 'numeric', 'gt:0'],
            ], [
                'kelurahan_id.in'  => 'Kelurahan ID tidak ditemukan.',
                'tahun.in'         => "Tahun harus {$this->tahun}.",
                '*.required'       => ':attribute wajib diisi.',
                '*.integer'        => ':attribute harus berupa angka bulat.',
                '*.numeric'        => ':attribute harus berupa angka.',
                'luas_wilayah_km2.gt' => 'Luas wilayah harus lebih dari 0.',
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $pesan) {
                    $this->errors[] = "Baris {$baris}: {$pesan}";
                }
                continue;
            }

            $valid[(int) $data['kelurahan_id']] = $data;
        }

        if (! empty($this->errors)) {
            return;
        }

        if (empty($valid)) {
            $this->errors[] = 'File tidak berisi data.';
            return;
        }

        DB::transaction(function () use ($valid) {
            foreach ($valid as $data) {
                Penduduk::updateOrCreate(
                    [
                        'kelurahan_id' => $data['kelurahan_id'],
                        'tahun'        => $this->tahun,
                    ],
                    [
                        'jumlah_penduduk'  => $data['jumlah_penduduk'],
                        'luas_wilayah_km2' => $data['luas_wilayah_km2'],
                    ]
                );
                $this->imported++;
            }
        });
    }
}