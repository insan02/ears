<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArsipMasuk;
use App\Models\MediaInformasi;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // 1. Total Arsip Masuk
        $totalArsip = ArsipMasuk::count();

        // 2. Arsip Masuk Bulan Ini
        $bulanIniArsip = ArsipMasuk::whereMonth('tanggal_terima', now()->month)
            ->whereYear('tanggal_terima', now()->year)
            ->count();

        // 3. Tren Arsip Masuk (Tahun Ini) for Chart
        $arsipTrendData = ArsipMasuk::selectRaw('MONTH(tanggal_terima) as bulan, COUNT(*) as total')
            ->whereYear('tanggal_terima', date('Y'))
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        // Fill 1-12 months with 0 if missing
        $arsipBulananData = [];
        for ($m = 1; $m <= 12; $m++) {
            $arsipBulananData[] = $arsipTrendData[$m] ?? 0;
        }

        // Fetch Media Info
        // Ambil tepat 10 data terbaru untuk mengisi 2 baris x 5 kolom
        $mediaInfo = MediaInformasi::orderBy('tanggal', 'desc')->take(10)->get();

        return view('landing', compact('totalArsip', 'bulanIniArsip', 'arsipBulananData', 'mediaInfo'));
    }

    public function semuaBerita(Request $request)
    {
        $query = MediaInformasi::query();

        // Fitur Pencarian Berita
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        // Tampilkan 15 berita per halaman
        $mediaInfo = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        return view('semua-berita', compact('mediaInfo'));
    }

    public function visiMisi()
    {
        return view('visi-misi');
    }

    public function sejarah()
    {
        return view('sejarah');
    }

    public function penghargaan()
    {
        return view('penghargaan');
    }

    public function struktur()
    {
        return view('struktur');
    }
}
