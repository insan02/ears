<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Arsip;
use App\Models\ArsipMasuk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ==========================================
        // 0. FILTER HELPER
        // ==========================================
        $applyFilters = function ($query) use ($request) {
            if ($request->filled('bulan')) {
                $query->whereMonth('tanggal_kerja', $request->bulan);
            }
            if ($request->filled('minggu')) {
                 $query->whereRaw('FLOOR((Day(tanggal_kerja) - 1) / 7) + 1 = ?', [$request->minggu]);
            }
             if ($request->filled('unit_kerja')) {
                $query->where('unit_kerja', $request->unit_kerja);
            }
            return $query;
        };

        // ==========================================
        // 1. DATA RINGKAS PEMINJAMAN
        // ==========================================
        $dipinjam = DetailPeminjaman::whereHas('peminjaman', function ($q) {
            $q->where('status', 'Sedang Dipinjam');
        })->count();

        $kembali = DetailPeminjaman::whereHas('peminjaman', function ($q) {
            $q->whereIn('status', ['Sudah Dikembalikan', 'Telah Dikembalikan']);
        })->count();

        $dataDipinjam = array_fill(0, 12, 0);
        $dataKembali = array_fill(0, 12, 0);
        $tahunIni = date('Y');

        $itemsTahunIni = DetailPeminjaman::with('peminjaman')
            ->whereHas('peminjaman', function ($q) use ($tahunIni) {
                $q->whereYear('tanggal_pinjam', $tahunIni);
            })->get();

        foreach ($itemsTahunIni as $item) {
            if (!$item->peminjaman) continue;
            $bulanIndex = (int) date('m', strtotime($item->peminjaman->tanggal_pinjam)) - 1;

            if ($item->peminjaman->status == 'Sedang Dipinjam') $dataDipinjam[$bulanIndex]++;
            else $dataKembali[$bulanIndex]++;
        }

        $mediaHardfile = DetailPeminjaman::where('jenis_arsip', 'Hardfile')->count();
        $mediaSoftfile = DetailPeminjaman::where('jenis_arsip', 'Softfile')->count();

        // ==========================================
        // 2. DATA MONITORING KARYAWAN
        // ==========================================
        $pemilahan = $applyFilters(LogAktivitas::where('tahapan', 'Pemilahan'))->sum('jumlah_box_selesai');
        $pendataan = $applyFilters(LogAktivitas::where('tahapan', 'Pendataan'))->sum('jumlah_box_selesai');
        $pelabelan = $applyFilters(LogAktivitas::where('tahapan', 'Pelabelan'))->sum('jumlah_box_selesai');
        $alihMedia = $applyFilters(LogAktivitas::where('tahapan', 'Alih Media'))->sum('jumlah_box_selesai');
        $inputEArsip = $applyFilters(LogAktivitas::where('tahapan', 'Input E-Arsip'))->sum('jumlah_box_selesai');

        // Papan Peringkat (Leaderboard) Kinerja Per Tahapan
        $stagesAll = ['Pemilahan', 'Pendataan', 'Pelabelan', 'Alih Media', 'Input E-Arsip'];
        $performancePerStage = [];

        foreach ($stagesAll as $stg) {
            $statsQuery = LogAktivitas::with('user')->where('tahapan', $stg)->whereNotNull('user_id');
            $statsQuery = $applyFilters($statsQuery);

            $stats = $statsQuery->selectRaw('user_id, SUM(jumlah_box_selesai) as total_selesai')
                ->groupBy('user_id')
                ->orderByDesc('total_selesai')
                ->get();

            $max = $stats->max('total_selesai') ?: 1;
            $stats = $stats->map(function($item) use ($max) {
                $item->persentase_visual = round(($item->total_selesai / $max) * 100);
                return $item;
            });

            $performancePerStage[$stg] = $stats;
        }

        // ==========================================
        // 3. CHARTS LAINNYA
        // ==========================================
        $tahapanChartData = [
            'labels' => ['Pemilahan', 'Pendataan', 'Pelabelan', 'Alih Media', 'Input E-Arsip'],
            'data' => [$pemilahan, $pendataan, $pelabelan, $alihMedia, $inputEArsip]
        ];

        $arsipTrendQuery = \App\Models\ArsipMasuk::query();
        if ($request->filled('unit_kerja')) $arsipTrendQuery->where('unit_asal', $request->unit_kerja);

        $arsipTrendData = $arsipTrendQuery->selectRaw('MONTH(tanggal_terima) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_terima', date('Y'))->groupBy('bulan')->pluck('total', 'bulan')->toArray();

        $arsipBulananData = [];
        for($m=1; $m<=12; $m++) $arsipBulananData[] = $arsipTrendData[$m] ?? 0;

        $arsipUnitQuery = \App\Models\ArsipMasuk::query();
        if ($request->filled('bulan')) $arsipUnitQuery->whereMonth('tanggal_terima', $request->bulan);

        $arsipUnitDataRaw = $arsipUnitQuery->selectRaw('unit_asal, COUNT(*) as total')
            ->whereNotNull('unit_asal')->where('unit_asal', '!=', '') // Mencegah label kosong
            ->groupBy('unit_asal')->orderByDesc('total')->take(10)->get();

        $arsipUnitChart = [
            'labels' => $arsipUnitDataRaw->pluck('unit_asal')->values()->toArray(),
            'data' => $arsipUnitDataRaw->pluck('total')->values()->toArray()
        ];

        $klasifikasiStats = Arsip::with('klasifikasi')->get()->groupBy(function($item) {
            return $item->klasifikasi ? substr($item->klasifikasi->kode_klasifikasi, 0, 2) : 'Lainnya';
        })->map->count()->sortDesc()->take(7);

        $arsipKlasifikasiChart = [
            'labels' => $klasifikasiStats->keys()->values()->toArray(),
            'data' => $klasifikasiStats->values()->toArray()
        ];

        $arsipTahunStats = Arsip::select('tahun', DB::raw('count(*) as total'))
            ->whereNotNull('tahun')->where('tahun', '!=', '')
            ->groupBy('tahun')->orderBy('tahun', 'asc')->get();
        $arsipTahunChart = [
            'labels' => $arsipTahunStats->pluck('tahun')->values()->toArray(),
            'data' => $arsipTahunStats->pluck('total')->values()->toArray()
        ];

        $arsipMediaStats = Arsip::select('jenis_media', DB::raw('count(*) as total'))
            ->whereNotNull('jenis_media')->where('jenis_media', '!=', '')
            ->groupBy('jenis_media')->get();
        $arsipMediaChart = [
            'labels' => $arsipMediaStats->pluck('jenis_media')->values()->toArray(),
            'data' => $arsipMediaStats->pluck('total')->values()->toArray()
        ];

        $arsipStatusStats = Arsip::select('tindakan_akhir', DB::raw('count(*) as total'))
            ->whereNotNull('tindakan_akhir')->where('tindakan_akhir', '!=', '')
            ->groupBy('tindakan_akhir')->get();
        $arsipStatusChart = [
            'labels' => $arsipStatusStats->pluck('tindakan_akhir')->values()->toArray(),
            'data' => $arsipStatusStats->pluck('total')->values()->toArray()
        ];

        // VARIABEL UMUM
        $totalArsip = Arsip::count();
        $totalBox = LogAktivitas::sum('jumlah_box_selesai');
        $bulanIniArsip = ArsipMasuk::whereMonth('tanggal_terima', date('m'))->whereYear('tanggal_terima', date('Y'))->count();
        $allPics = User::all();
        $allUnits = \App\Models\Unit::orderBy('nama_unit', 'asc')->get();

        return view('beranda', compact(
            'dipinjam', 'kembali', 'dataDipinjam', 'dataKembali',
            'totalArsip', 'totalBox', 'inputEArsip', 'bulanIniArsip',
            'pemilahan', 'pendataan', 'pelabelan', 'alihMedia',
            'allPics', 'allUnits',
            'tahapanChartData', 'arsipBulananData', 'arsipUnitChart',
            'mediaHardfile', 'mediaSoftfile',
            'arsipKlasifikasiChart', 'arsipTahunChart', 'arsipMediaChart', 'arsipStatusChart',
            'performancePerStage'
        ));
    }
}
