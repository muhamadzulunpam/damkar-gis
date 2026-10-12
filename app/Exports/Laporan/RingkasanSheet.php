<?php

namespace App\Exports\Laporan;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RingkasanSheet implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithTitle
{
    public function __construct(
        protected array $laporan
    ) {
    }

    public function collection(): Collection
    {
        $rows = collect();

        $rows->push([
            'LAPORAN ANALISIS TINGKAT KERAWANAN',
            'Periode ' . ($this->laporan['label'] ?? '-'),
            'Dicetak pada ' . (
                $this->laporan['dicetak_pada']?->format('d-m-Y H:i') ?? '-'
            ),
            'Dicetak oleh ' . ($this->laporan['dicetak_oleh'] ?? '-'),
        ]);

        $rows->push(['']);

        $rows->push([
            'Jenis Kejadian',
            'Jumlah Kelurahan',
            'Total Kejadian',
            'Tinggi',
            'Sedang',
            'Rendah',
            'Status',
        ]);

        foreach (($this->laporan['jenis'] ?? []) as $data) {
            $ringkasan = $data['ringkasan'] ?? [];

            $run = $data['run'] ?? null;

            $rows->push([
                $data['nama'] ?? '-',
                $ringkasan['jumlah_kelurahan'] ?? 0,
                $ringkasan['total_kejadian'] ?? 0,
                $ringkasan['tinggi'] ?? 0,
                $ringkasan['sedang'] ?? 0,
                $ringkasan['rendah'] ?? 0,
                $run ? 'Sudah dianalisis' : 'Belum ada hasil',
            ]);
        }

        $rows->push(['']);

        $rows->push([
            'Catatan metode',
            'K-Means dengan k = 3 (Tinggi, Sedang, Rendah). Tingkat kerawanan bersifat relatif terhadap kelurahan lain dalam periode yang dipilih.',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [];
    }

    public function title(): string
    {
        return 'Ringkasan';
    }
}
