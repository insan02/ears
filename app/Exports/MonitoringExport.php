<?php

namespace App\Exports;

use App\Models\LogAktivitas;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths; // <-- Gunakan ini, hapus ShouldAutoSize

class MonitoringExport implements FromQuery, WithHeadings, WithMapping, WithColumnWidths
{
    use Exportable;

    protected $search, $pic, $tahapan;

    public function __construct($search = null, $pic = null, $tahapan = null)
    {
        $this->search = $search;
        $this->pic = $pic;
        $this->tahapan = $tahapan;
    }

    public function query()
    {
        $query = LogAktivitas::with(['user', 'arsipMasuk'])->orderBy('created_at', 'desc');

        // PERBAIKAN: Gunakan LIKE
        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('nba', 'like', "%{$search}%")
                  ->orWhere('unit_kerja', 'like', "%{$search}%")
                  ->orWhere('tahapan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->pic) $query->where('user_id', $this->pic);
        if ($this->tahapan) $query->where('tahapan', $this->tahapan);

        return $query;
    }

    public function headings(): array
    {
        return [
            'ID', 'No. Berita Acara', 'Unit Kerja', 'PIC (Staf)', 'Tahapan',
            'Target Pekerjaan', 'Progress Selesai', 'Tanggal Pengerjaan', 'Status Kerja', 'Catatan'
        ];
    }

    // LEBAR KOLOM MANUAL AGAR EKSPOR EXCEL SELESAI DALAM HITUNGAN DETIK
    public function columnWidths(): array
    {
        return [
            'A' => 8,  // ID
            'B' => 25, // No Berita Acara
            'C' => 30, // Unit Kerja
            'D' => 25, // PIC
            'E' => 15, // Tahapan
            'F' => 18, // Target Pekerjaan
            'G' => 18, // Progress Selesai
            'H' => 20, // Tanggal Pengerjaan
            'I' => 15, // Status Kerja
            'J' => 40, // Catatan
        ];
    }

    public function map($log): array
    {
        $unitLabel = $log->tahapan == 'Alih Media' ? 'Lembar' : 'Box';
        return [
            $log->id,
            $log->nba,
            $log->unit_kerja,
            $log->user->nama ?? 'Unknown',
            $log->tahapan,
            $log->jumlah_box . " " . $unitLabel,
            $log->jumlah_box_selesai . " " . $unitLabel,
            $log->tanggal_kerja,
            $log->status_kerja,
            $log->keterangan ?? '-'
        ];
    }
}
