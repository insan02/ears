<?php

namespace App\Exports;

use App\Models\LimaPContent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LimaPExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        return LimaPContent::orderBy('id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'PIC (Person In Charge)',
            'Pembagian Area',
            'Kesepakatan',
            'Visi & Misi',
            'Struktur',
            'Jadwal Kegiatan',
            'Kaizen'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->pic,
            $row->pembagian_area,
            $row->kesepakatan,
            $row->visi_misi,
            $row->struktur,
            $row->jadwal_kegiatan,
            $row->kaizen
        ];
    }

    // Menambahkan styling huruf tebal (Bold) pada baris pertama (Header)
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
