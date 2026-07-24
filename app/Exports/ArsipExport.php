<?php

namespace App\Exports;

use App\Models\Arsip;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ArsipExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithDrawings, WithCustomStartCell, WithEvents
{
    protected $ids;
    protected $search;
    protected $sort;
    protected $filter_tindakan;
    protected $filter_tahun;

    public function __construct($ids = null, $search = null, $sort = null, $filter_tindakan = null, $filter_tahun = null)
    {
        $this->ids = $ids;
        $this->search = $search;
        $this->sort = $sort;
        $this->filter_tindakan = $filter_tindakan;
        $this->filter_tahun = $filter_tahun;
    }

    public function query()
    {
        $query = Arsip::with('klasifikasi');

        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        } else {
            // Apply Filters (Search & Tindakan)
            if ($this->search) {
                $search = $this->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_berkas', 'like', "%{$search}%")
                      ->orWhere('no_berkas', 'like', "%{$search}%")
                      ->orWhere('isi', 'like', "%{$search}%")
                      ->orWhereHas('klasifikasi', function($q2) use ($search) {
                          $q2->where('kode_klasifikasi', 'like', "%{$search}%");
                      });
                });
            }

            if ($this->filter_tindakan) {
                $query->where('tindakan_akhir', $this->filter_tindakan);
            }

            if ($this->filter_tahun) {
                $query->where('tahun', $this->filter_tahun);
            }
        }

        // Apply same sorting as index
        switch ($this->sort) {
            case 'oldest':
                $query->orderBy('id', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('tahun', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('tahun', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'No Berkas',
            'Kode Klasifikasi',
            'Nama Berkas',
            'Isi Berkas',
            'Tahun',
            'Tanggal',
            'Jumlah',
            'Hak Akses',
            'Masa Simpan',
            'Tindakan',
            'Box'
        ];
    }

    public function map($arsip): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $arsip->no_berkas,
            $arsip->klasifikasi->kode_klasifikasi ?? '-',
            $arsip->nama_berkas,
            $arsip->isi,
            $arsip->tahun,
            $arsip->tanggal_masuk,
            $arsip->jumlah,
            $arsip->hak_akses ?? '-',
            $arsip->masa_simpan ?? '-',
            $arsip->tindakan_akhir ?? '-',
            $arsip->no_box ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            5 => ['font' => ['bold' => true]],
        ];
    }

    public function startCell(): string
    {
        return 'A5';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo PT Semen Padang');
        $drawing->setPath(public_path('images/logo-sp.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');

        return [$drawing];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getHighestRow();
                $lastColumn = 'L'; // 12 Columns (A-L)

                // Merge cells for Title (Centered across table)
                $sheet->mergeCells("B1:{$lastColumn}1");
                $sheet->mergeCells("B2:{$lastColumn}2");
                $sheet->mergeCells("B3:{$lastColumn}3");

                // Set Title Text
                $sheet->setCellValue('B1', 'PT SEMEN PADANG');
                $sheet->setCellValue('B2', 'DAFTAR ARSIP DOKUMEN');
                $sheet->setCellValue('B3', 'Indarung, Padang 25237, Sumatera Barat');

                // Style Title
                $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED));
                $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('B3')->getFont()->setSize(10);

                // Align Title
                $sheet->getStyle("B1:B3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Style Table Header (Row 5)
                $headerRange = "A5:{$lastColumn}5";
                $sheet->getStyle($headerRange)->getFill()
                      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFFCE4E4'); // Light red
                
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // Style Whole Table (Borders)
                $tableRange = "A5:{$lastColumn}{$highestRow}";
                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                
                // Center Align specific columns: No(A), Tahun(F), Tanggal(G), Jumlah(H), Box(L)
                // Columns: A B C D E F G H I J K L
                $sheet->getStyle("A6:A{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // No
                $sheet->getStyle("F6:H{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Tahun, Tanggal, Jumlah
                $sheet->getStyle("L6:L{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Box
            },
        ];
    }
}