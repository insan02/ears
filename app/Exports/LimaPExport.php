<?php

namespace App\Exports;

use App\Models\LimaPContent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LimaPExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return LimaPContent::all();
    }

    public function headings(): array
    {
        return [
            'ID', 'PIC (Person In Charge)', 'Kesepakatan', 'Visi & Misi',
            'Pembagian Area', 'Struktur', 'Jadwal Kegiatan', 'Kaizen'
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->pic,
            $row->kesepakatan,
            $row->visi_misi,
            $row->pembagian_area,
            $row->struktur,
            $row->jadwal_kegiatan,
            $row->kaizen
        ];
    }
}
