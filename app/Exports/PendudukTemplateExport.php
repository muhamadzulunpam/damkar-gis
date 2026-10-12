<?php

namespace App\Exports;

use App\Models\Kelurahan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PendudukTemplateExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles
{
    public function __construct(protected int $tahun)
    {
    }

    public function collection(): Collection
    {
        return Kelurahan::with('kecamatan')
            ->orderBy('kecamatan_id')
            ->orderBy('nama')
            ->get()
            ->map(fn ($kelurahan) => [
                $kelurahan->id,
                $kelurahan->nama,
                $kelurahan->kecamatan?->nama,
                $this->tahun,
                null,
                null,
            ]);
    }

    public function headings(): array
    {
        return [
            'Kelurahan ID',
            'Kelurahan',
            'Kecamatan',
            'Tahun',
            'Jumlah Penduduk',
            'Luas Wilayah (km²)',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}