<?php

namespace App\Exports;

use App\Models\ArsipMusnah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class ArsipMusnahExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithCustomStartCell, WithDrawings, WithEvents
{
    protected $search;

    public function __construct($search = '')
    {
        $this->search = $search;
    }

    public function collection()
    {
        // PERBAIKAN: Tambahkan withTrashed() di sini agar data yang memiliki deleted_at tetap terbaca!
        $query = ArsipMusnah::withTrashed()->with('klasifikasi');

        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_berkas', 'like', "%{$search}%")
                  ->orWhere('no_berkas', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan tanggal musnah paling baru
        return $query->orderBy('deleted_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Klasifikasi',
            'Nama Berkas',
            'Uraian Berkas',
            'Tahun',
            'Tgl Masuk',
            'Jumlah',
            'Box',
            'Unit Pengolah',
            'Tindakan Akhir',
            'Tanggal Dimusnahkan'
        ];
    }

    public function map($arsip): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $arsip->klasifikasi->kode_klasifikasi ?? '-',
            $arsip->nama_berkas ?? '-',
            $arsip->isi ?? '-',
            $arsip->tahun ?? '-',
            $arsip->tanggal_masuk ? Carbon::parse($arsip->tanggal_masuk)->format('d/m/Y') : '-',
            $arsip->jumlah ?? '-',
            $arsip->no_box ?? '-',
            $arsip->unit_pengolah ?? '-',
            $arsip->tindakan_akhir ?? '-',
            $arsip->deleted_at ? Carbon::parse($arsip->deleted_at)->format('d/m/Y') : '-'
        ];
    }

    public function startCell(): string
    {
        return 'A5'; // Sisakan ruang untuk kop surat
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
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $lastColumn = 'K'; // Sampai kolom K (11 Kolom)

                // -----------------------------
                // 1. STYLING KOP / JUDUL SURAT
                // -----------------------------
                $sheet->mergeCells("B1:{$lastColumn}1");
                $sheet->mergeCells("B2:{$lastColumn}2");
                $sheet->mergeCells("B3:{$lastColumn}3");

                $sheet->setCellValue('B1', 'PT SEMEN PADANG');
                $sheet->setCellValue('B2', 'DAFTAR ARSIP MUSNAH');
                $sheet->setCellValue('B3', 'Indarung, Padang 25237, Sumatera Barat');

                $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED));
                $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('B3')->getFont()->setSize(10);
                $sheet->getStyle("B1:B3")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // -----------------------------
                // 2. STYLING HEADER TABEL (Baris 5)
                // -----------------------------
                $headerRange = "A5:{$lastColumn}5";
                $sheet->getStyle($headerRange)->getFill()
                      ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFE92027'); // Merah Gelap khas Semen Padang
                $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE));
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // -----------------------------
                // 3. STYLING SELURUH TABEL (Border & Alignment)
                // -----------------------------
                if ($highestRow >= 5) {
                    $tableRange = "A5:{$lastColumn}{$highestRow}";
                    $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    // Rata Tengah untuk angka/tanggal
                    $sheet->getStyle("A6:A{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // No
                    $sheet->getStyle("E6:H{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Tahun, Tgl, Jml, Box
                    $sheet->getStyle("K6:K{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Tgl Musnah
                }
            }
        ];
    }
}
