<?php

namespace App\Exports;

use App\Models\DetailPeminjaman;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths; // <-- Mengganti ShouldAutoSize
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// PASTIKAN ShouldAutoSize DIHAPUS DARI IMPLEMENTS
class PeminjamanExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithColumnFormatting, WithDrawings
{
    protected $filters;
    private $rowNumber = 0;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = DetailPeminjaman::query()->with(['peminjaman', 'arsip']);
        $request = $this->filters;

        // 1. OPTIMASI FILTER SEARCH MENGGUNAKAN FULLTEXT
        if (isset($request['search']) && $request['search'] != null) {
            $keyword = $request['search'];
            $searchTerm = '*' . $keyword . '*';

            $query->where(function ($q) use ($searchTerm, $keyword) {
                $q->whereHas('peminjaman', function ($qP) use ($searchTerm) {
                    $qP->whereRaw("MATCH(nama_peminjam, nip, unit_peminjam, jabatan_peminjam, keperluan) AGAINST(? IN BOOLEAN MODE)", [$searchTerm]);
                })
                ->orWhereRaw("MATCH(nama_arsip, no_box) AGAINST(? IN BOOLEAN MODE)", [$searchTerm])
                ->orWhereHas('arsip', function ($qArsip) use ($searchTerm, $keyword) {
                    $qArsip->whereRaw("MATCH(nama_berkas, isi) AGAINST(? IN BOOLEAN MODE)", [$searchTerm])
                           ->orWhere('no_berkas', 'like', "%{$keyword}%");
                });
            });
        }

        // 2. FILTER STATUS
        if (isset($request['status']) && $request['status'] != 'All') {
            $status = $request['status'];
            $query->whereHas('peminjaman', function ($q) use ($status) {
                if ($status == 'Sudah Dikembalikan' || $status == 'Telah Dikembalikan') {
                    $q->whereIn('status', ['Sudah Dikembalikan', 'Telah Dikembalikan']);
                } else {
                    $q->where('status', $status);
                }
            });
        }

        // 3. FILTER MEDIA
        if (isset($request['media']) && $request['media'] != 'All') {
            $query->where('jenis_arsip', $request['media']);
        }

        // 4. FILTER KEAMANAN
        if (isset($request['keamanan']) && $request['keamanan'] != 'All') {
            $query->where('hak_akses', $request['keamanan']);
        }

        // 5. FILTER TANGGAL
        if (isset($request['start_date']) && $request['start_date'] != null) {
            $query->whereHas('peminjaman', function ($q) use ($request) {
                $q->whereDate('tanggal_pinjam', '>=', $request['start_date']);
            });
        }
        if (isset($request['end_date']) && $request['end_date'] != null) {
            $query->whereHas('peminjaman', function ($q) use ($request) {
                $q->whereDate('tanggal_pinjam', '<=', $request['end_date']);
            });
        }

        $query->select('detail_peminjaman.*')
            ->join('peminjaman', 'detail_peminjaman.peminjaman_id', '=', 'peminjaman.id')
            ->orderBy('peminjaman.created_at', 'desc');

        return $query;
    }

    public function headings(): array
    {
        return [
            ['PT SEMEN PADANG'],
            ['DAFTAR ARSIP DOKUMEN'],
            ['Indarung, Padang 25237, Sumatera Barat'],
            [''],
            [
                'No', 'Tanggal', 'Peminjam', 'NIP', 'Jabatan', 'Unit',
                'Keperluan', 'Nama Arsip', 'Hak Akses', 'Jenis Arsip',
                'Otentikasi', 'No. Box', 'Status'
            ]
        ];
    }

    // PENGATURAN LEBAR KOLOM MANUAL AGAR RAM SERVER AMAN
    public function columnWidths(): array
    {
        return [
            'A' => 6,  // No
            'B' => 15, // Tanggal
            'C' => 25, // Peminjam
            'D' => 15, // NIP
            'E' => 20, // Jabatan
            'F' => 25, // Unit
            'G' => 30, // Keperluan
            'H' => 40, // Nama Arsip
            'I' => 15, // Hak Akses
            'J' => 15, // Jenis Arsip
            'K' => 15, // Otentikasi
            'L' => 12, // No. Box
            'M' => 20, // Status
        ];
    }

    public function map($detail): array
    {
        $this->rowNumber++;
        $peminjaman = $detail->peminjaman;

        $namaArsip = $detail->arsip ? $detail->arsip->nama_berkas : $detail->nama_arsip;
        $noBox = ($detail->arsip && $detail->arsip->no_box) ? $detail->arsip->no_box : ($detail->no_box ?? '-');

        $hakAkses = $detail->hak_akses;
        if (empty($hakAkses) && $detail->arsip && $detail->arsip->klasifikasi) {
            $hakAkses = $detail->arsip->klasifikasi->hak_akses;
        }

        return [
            $this->rowNumber,
            $peminjaman ? Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') : '-',
            $peminjaman->nama_peminjam ?? '-',
            $peminjaman->nip ? " " . $peminjaman->nip : '-',
            $peminjaman->jabatan_peminjam ?? '-',
            $peminjaman->unit_peminjam ?? '-',
            $peminjaman->keperluan ?? '-',
            $namaArsip,
            $hakAkses ?? '-',
            $detail->jenis_arsip,
            $detail->detail_fisik ?? '-',
            $noBox,
            $peminjaman->status ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:M2');
        $sheet->mergeCells('A3:M3');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFF0000'], 'size' => 14],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle('A3')->applyFromArray([
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ]);

        $sheet->getStyle('A5:M5')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFCCCC']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        $highestRow = $sheet->getHighestRow();
        if ($highestRow >= 6) {
            $sheet->getStyle('A6:M' . $highestRow)->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            ]);

            $sheet->getStyle('A6:A' . $highestRow)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('B6:B' . $highestRow)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('D6:F' . $highestRow)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('I6:M' . $highestRow)->getAlignment()->setHorizontal('center');
        }

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_TEXT, // NIP
            'L' => NumberFormat::FORMAT_TEXT, // No. Box
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo PT Semen Padang');
        $drawing->setPath(public_path('images/logo-sp.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);
        return [$drawing];
    }
}
