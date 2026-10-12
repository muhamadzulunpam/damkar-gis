<?php

namespace App\Exports\Laporan;

use Maatwebsite\Excel\Concerns\Export;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class LaporanKerawananExport implements Export, WithMultipleSheets
{
    use Exportable;

    protected array $laporan;

    public function __construct(array $laporan)
    {
        $this->laporan = $laporan;
    }

    public function sheets(): array
    {
        $sheets = [
            new RingkasanSheet($this->laporan),
        ];

        foreach ($this->laporan['jenis'] as $data) {
            if (($data['run'] ?? null) !== null) {
                $sheets[] = new DetailSheet($data);
            }
        }

        return $sheets;
    }
}
