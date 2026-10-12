<?php

namespace App\Exports\Laporan;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DetailSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    public function __construct(protected array $data)
    {
    }

    public function collection(): Collection
    {
        return collect($this->data['baris'] ?? [])->map(function ($row) {
            return [
                'Kecamatan' => $row->kelurahan?->kecamatan?->nama ?? '-',
                'Kelurahan' => $row->kelurahan?->nama ?? '-',
                'Jumlah Kejadian' => (int) ($row->jumlah_kejadian ?? 0),
                'Kepadatan Penduduk (jiwa/km²)' => is_numeric($row->kepadatan_penduduk ?? null)
                    ? round((float) $row->kepadatan_penduduk, 2)
                    : null,
                'Tingkat Kerawanan' => $row->tingkat_kerawanan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kecamatan',
            'Kelurahan',
            'Jumlah Kejadian',
            'Kepadatan Penduduk (jiwa/km²)',
            'Tingkat Kerawanan',
        ];
    }

    public function title(): string
    {
        $name = preg_replace('/[\\\\\/\?\*\[\]:]/', '', (string) ($this->data['nama'] ?? 'Detail'));
        return mb_substr($name ?: 'Detail', 0, 31);
    }
}
