<?php

namespace App\Exports;

use App\Models\Arsip;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class ArsipExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithCustomStartCell, WithDrawings, WithEvents
{
    // VARIABEL YANG DIPERBAIKI SESUAI CONTROLLER
    protected $ids;
    protected $search;
    protected $sort;
    protected $filter_status;
    protected $filter_tahun;
    protected $filter_hak_akses;

    // Penanda baris untuk di-merge nanti
    protected $mergedRows = [];
    protected $currentRow = 5; // Baris mulai data (karena headernya di A5)

    // CONSTRUCTOR DIPERBARUI UNTUK MENERIMA 6 PARAMETER
    public function __construct($ids = [], $search = '', $sort = 'newest', $filter_status = '', $filter_tahun = '', $filter_hak_akses = '')
    {
        $this->ids = $ids;
        $this->search = $search;
        $this->sort = $sort;
        $this->filter_status = $filter_status;
        $this->filter_tahun = $filter_tahun;
        $this->filter_hak_akses = $filter_hak_akses;
    }

    public function collection()
    {
        $query = Arsip::with('klasifikasi');

        if (!empty($this->ids)) {
            $query->whereIn('id', $this->ids);
        } else {
            // Apply Filters (Search, Status, Hak Akses, Tahun)
            if ($this->search) {
                $search = $this->search;
                $searchTerm = '*' . $search . '*';
                $query->where(function($q) use ($searchTerm, $search) {
                    $q->whereRaw("MATCH(nama_berkas, isi) AGAINST(? IN BOOLEAN MODE)", [$searchTerm])
                      ->orWhere('no_berkas', 'like', "%{$search}%") // <-- Kembalikan no_berkas pakai LIKE
                      ->orWhere('unit_pengolah', 'like', "%{$search}%")
                      ->orWhere('no_box', 'like', "%{$search}%")
                      ->orWhereHas('klasifikasi', function($q2) use ($search) {
                          $q2->where('kode_klasifikasi', 'like', "%{$search}%");
                      });
                });
            }

            if ($this->filter_status) {
                $query->where('tindakan_akhir', 'like', '%' . $this->filter_status . '%');
            }

            if ($this->filter_hak_akses) {
                $query->where('hak_akses', $this->filter_hak_akses);
            }

            if ($this->filter_tahun) {
                $query->where('tahun', $this->filter_tahun);
            }
        }

        // Murni urutan ID seperti di Index Web
        switch ($this->sort) {
            case 'oldest': $query->orderBy('id', 'asc'); break;
            case 'year_desc': $query->orderBy('tahun', 'desc')->orderBy('id', 'desc'); break;
            case 'year_asc': $query->orderBy('tahun', 'asc')->orderBy('id', 'desc'); break;
            case 'newest':
            default: $query->orderBy('id', 'desc'); break;
        }

        return $query->get();
    }

    public function headings(): array
    {
        // 14 Kolom Standar Import/Export
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
            'Box',
            'Unit Pengolah',
            'Jenis Media'
        ];
    }

    public function map($arsip): array
    {
        static $no = 0;
        $this->currentRow++;

        // DETEKSI LOGIKA MERGE (Hanya ada 1 isi yang terisi)
        $checkFields = [
            $arsip->no_berkas, $arsip->nama_berkas, $arsip->isi,
            $arsip->tahun, $arsip->tanggal_masuk, $arsip->jumlah,
            $arsip->hak_akses, $arsip->masa_simpan, $arsip->tindakan_akhir,
            $arsip->no_box, $arsip->unit_pengolah, $arsip->jenis_media
        ];

        $nonEmpty = 0;
        $mergedText = '';
        foreach($checkFields as $f) {
            if ($f !== null && trim($f) !== '' && trim($f) !== '-') {
                $nonEmpty++;
                $mergedText = $f;
            }
        }

        // Jika hanya 1 kolom yang terisi, anggap ini baris Merge/Header Grup
        if ($nonEmpty === 1) {
            $this->mergedRows[] = $this->currentRow;

            // Kembalikan isi hanya di Kolom A (No), sisanya kosong
            return [
                $mergedText, '', '', '', '', '', '', '', '', '', '', '', '', ''
            ];
        }

        // JIKA BUKAN BARIS MERGE:
        $no++;
        return [
            $no,
            $arsip->no_berkas,
            $arsip->klasifikasi->kode_klasifikasi ?? '-',
            $arsip->nama_berkas,
            $arsip->isi,
            $arsip->tahun,
            $arsip->tanggal_masuk ? Carbon::parse($arsip->tanggal_masuk)->format('d/m/Y') : '-',
            $arsip->jumlah,
            $arsip->hak_akses ?? '-',
            $arsip->masa_simpan ?? '-',
            $arsip->tindakan_akhir ?? '-',
            $arsip->no_box ?? '-',
            $arsip->unit_pengolah ?? '-',
            $arsip->jenis_media ?? '-'
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
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $lastColumn = 'N'; // Sampai Kolom N (14 Kolom)

                // -----------------------------
                // 1. STYLING KOP / JUDUL SURAT
                // -----------------------------
                $sheet->mergeCells("B1:{$lastColumn}1");
                $sheet->mergeCells("B2:{$lastColumn}2");
                $sheet->mergeCells("B3:{$lastColumn}3");

                $sheet->setCellValue('B1', 'PT SEMEN PADANG');
                $sheet->setCellValue('B2', 'DAFTAR ARSIP DOKUMEN');
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
                      ->getStartColor()->setARGB('FFFCE4E4'); // Merah muda
                $sheet->getStyle($headerRange)->getFont()->setBold(true);
                $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                // -----------------------------
                // 3. STYLING SELURUH TABEL (Border & Alignment)
                // -----------------------------
                $tableRange = "A5:{$lastColumn}{$highestRow}";
                $sheet->getStyle($tableRange)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                // Rata Tengah untuk kolom spesifik
                $sheet->getStyle("A6:B{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // No, No Berkas
                $sheet->getStyle("F6:H{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Thn, Tgl, Jml
                $sheet->getStyle("L6:L{$highestRow}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Box

                // -----------------------------
                // 4. EKSEKUSI MERGE BARIS (Satu baris full)
                // -----------------------------
                foreach ($this->mergedRows as $rowNum) {
                    // Merge dari Kolom A sampai N
                    $sheet->mergeCells("A{$rowNum}:{$lastColumn}{$rowNum}");

                    // Set isi tulisan di tengah, tebal, dan huruf kapital
                    $sheet->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("A{$rowNum}")->getFont()->setBold(true);

                    // Set background warna abu-abu muda
                    $sheet->getStyle("A{$rowNum}:{$lastColumn}{$rowNum}")->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()->setARGB('FFF2F2F2');
                }
            }
        ];
    }
}
