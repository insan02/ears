<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\RiwayatMonitoring;
use App\Models\User;
use App\Models\ArsipMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringKaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('tahapan', 'like', "%{$search}%")
                ->orWhere('unit_kerja', 'like', "%{$search}%")
                ->orWhere('nba', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('pic')) {
            $query->where('user_id', $request->pic);
        }

        if ($request->filled('tahapan')) {
            $query->where('tahapan', $request->tahapan);
        }

        $monitoring = $query->paginate(15)->withQueryString();
        $users = User::all();

        $total = ArsipMasuk::count();
        $bulanIni = ArsipMasuk::whereMonth('tanggal_terima', now()->month)->whereYear('tanggal_terima', now()->year)->count();

        $pemilahan = LogAktivitas::where('tahapan', 'Pemilahan')->count();
        $pendataan = LogAktivitas::where('tahapan', 'Pendataan')->count();
        $pelabelan = LogAktivitas::where('tahapan', 'Pelabelan')->count();
        $alihMedia = LogAktivitas::where('tahapan', 'Alih Media')->count();
        $inputEArsip = LogAktivitas::where('tahapan', 'Input E-Arsip')->count();

        return view('monitoring.index', compact('monitoring', 'total', 'bulanIni', 'pemilahan', 'pendataan', 'pelabelan', 'alihMedia', 'inputEArsip', 'users'));
    }

    public function create()
    {
        $users = User::all();
        $arsipMasuk = ArsipMasuk::all();
        return view('monitoring.create', compact('users', 'arsipMasuk'));
    }

    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'tahapan' => 'required|string|in:Pemilahan,Pendataan,Pelabelan,Alih Media,Input E-Arsip',
            'jumlah_box_selesai' => 'nullable|integer|min:0',
            'tanggal_kerja' => 'required|date',
            'keterangan' => 'nullable|string',
        ];

        $rules['arsip_masuk_id'] = $request->tahapan != 'Alih Media' ? 'required|exists:arsip_masuk,id' : 'nullable';
        $request->validate($rules);

        $arsipMasukId = null; $nba = null; $jumlahBox = 0; $unitKerja = '-';
        $jumlahBoxSelesai = $request->jumlah_box_selesai ?? 0;

        if ($request->tahapan != 'Alih Media') {
            $arsipMasuk = ArsipMasuk::findOrFail($request->arsip_masuk_id);
            $arsipMasukId = $arsipMasuk->id;
            $nba = $arsipMasuk->nomor_berita_acara;
            $jumlahBox = $arsipMasuk->jumlah_box_masuk;
            $unitKerja = $arsipMasuk->unit_asal;

            // CEK LIMIT BOX
            $tahapanGroup = in_array($request->tahapan, ['Pemilahan', 'Pendataan', 'Pelabelan'])
                ? ['Pemilahan', 'Pendataan', 'Pelabelan'] : [$request->tahapan];

            $totalSedangDikerjakan = LogAktivitas::where('arsip_masuk_id', $arsipMasukId)
                                        ->whereIn('tahapan', $tahapanGroup)
                                        ->sum('jumlah_box_selesai');

            if (($totalSedangDikerjakan + $jumlahBoxSelesai) > $jumlahBox) {
                return back()->withErrors(['jumlah_box_selesai' => "Total box pada Berita Acara ini melebihi batas ({$jumlahBox} Box). Saat ini sudah {$totalSedangDikerjakan} Box yang diselesaikan oleh tim."]);
            }
        }

        $log = LogAktivitas::create([
            'user_id' => $request->user_id,
            'arsip_masuk_id' => $arsipMasukId,
            'nba' => $nba,
            'tahapan' => $request->tahapan,
            'jumlah_box' => $jumlahBox,
            'jumlah_box_selesai' => $jumlahBoxSelesai,
            'tanggal_kerja' => $request->tanggal_kerja,
            'unit_kerja' => $unitKerja,
            'keterangan' => $request->keterangan,
            'status_kerja' => 'Proses',
        ]);

        // Simpan Riwayat Awal Tugas (Agar muncul di modal riwayat)
        RiwayatMonitoring::create([
            'log_aktivitas_id' => $log->id,
            'user_id' => Auth::id(),
            'tahapan' => $log->tahapan,
            'tanggal_kerja' => $log->tanggal_kerja,
            'jumlah_box_selesai' => $log->jumlah_box_selesai,
            'jumlah_tambahan' => $jumlahBoxSelesai, // Menyimpan nilai awal
            'keterangan' => 'Tugas awal dimulai',
        ]);

        return redirect()->route('monitoring.index')->with('success', 'Data Monitoring berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Hanya Admin yang dapat mengedit data ini.');
        $monitoring = LogAktivitas::findOrFail($id);
        $users = User::all();
        $arsipMasuk = ArsipMasuk::all();
        return view('monitoring.edit', compact('monitoring', 'users', 'arsipMasuk'));
    }

    public function update(Request $request, string $id)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Hanya Admin yang dapat mengedit data ini.');
        $logAktivitas = LogAktivitas::findOrFail($id);

        if ($logAktivitas->status_kerja == 'Selesai') {
            return redirect()->back()->with('error', 'Data yang sudah selesai tidak dapat diedit!');
        }

        $rules = [
            'user_id' => 'required|exists:users,id',
            'tahapan' => 'required|string|in:Pemilahan,Pendataan,Pelabelan,Alih Media,Input E-Arsip',
            'jumlah_box_selesai' => 'nullable|integer|min:0',
            'tanggal_kerja' => 'required|date',
            'keterangan' => 'nullable|string',
        ];

        $rules['arsip_masuk_id'] = $request->tahapan != 'Alih Media' ? 'required|exists:arsip_masuk,id' : 'nullable';
        $request->validate($rules);

        $arsipMasukId = null; $nba = null; $jumlahBox = 0; $unitKerja = '-';
        $jumlahBoxSelesai = $request->jumlah_box_selesai ?? 0;

        if ($request->tahapan != 'Alih Media') {
            $arsipMasuk = ArsipMasuk::findOrFail($request->arsip_masuk_id);
            $arsipMasukId = $arsipMasuk->id;
            $nba = $arsipMasuk->nomor_berita_acara;
            $jumlahBox = $arsipMasuk->jumlah_box_masuk;
            $unitKerja = $arsipMasuk->unit_asal;

            $tahapanGroup = in_array($request->tahapan, ['Pemilahan', 'Pendataan', 'Pelabelan'])
                ? ['Pemilahan', 'Pendataan', 'Pelabelan'] : [$request->tahapan];

            $totalSedangDikerjakanLainnya = LogAktivitas::where('arsip_masuk_id', $arsipMasukId)
                                        ->whereIn('tahapan', $tahapanGroup)
                                        ->where('id', '!=', $logAktivitas->id)
                                        ->sum('jumlah_box_selesai');

            if (($totalSedangDikerjakanLainnya + $jumlahBoxSelesai) > $jumlahBox) {
                return back()->withErrors(['jumlah_box_selesai' => "Gagal update! Total box akan melebihi batas Arsip ({$jumlahBox} Box). Staf lain sudah menyelesaikan {$totalSedangDikerjakanLainnya} Box."]);
            }
        }

        // Hitung selisih jika Admin merubah jumlah box
        $selisih = $jumlahBoxSelesai - $logAktivitas->jumlah_box_selesai;

        RiwayatMonitoring::create([
            'log_aktivitas_id' => $logAktivitas->id,
            'user_id' => Auth::id(),
            'tahapan' => $request->tahapan,
            'tanggal_kerja' => $request->tanggal_kerja,
            'jumlah_box_selesai' => $jumlahBoxSelesai,
            'jumlah_tambahan' => $selisih,
            'keterangan' => 'Admin mengubah data ' . ($selisih !== 0 ? "(Selisih: {$selisih} Box)" : ""),
        ]);

        $logAktivitas->update([
            'user_id' => $request->user_id,
            'arsip_masuk_id' => $arsipMasukId,
            'nba' => $nba,
            'tahapan' => $request->tahapan,
            'jumlah_box' => $jumlahBox,
            'jumlah_box_selesai' => $jumlahBoxSelesai,
            'tanggal_kerja' => $request->tanggal_kerja,
            'unit_kerja' => $unitKerja,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('monitoring.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Hanya Admin yang dapat menghapus data ini.');
        $logAktivitas = LogAktivitas::findOrFail($id);

        if ($logAktivitas->status_kerja == 'Selesai') {
            return redirect()->back()->with('error', 'Data yang sudah selesai tidak dapat dihapus!');
        }

        $logAktivitas->delete();
        return redirect()->route('monitoring.index')->with('success', 'Data berhasil dihapus!');
    }

    public function advanceStage(string $id)
    {
        $monitoring = LogAktivitas::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() != $monitoring->user_id) {
            return redirect()->back()->with('error', 'Anda tidak berhak memajukan tahapan tugas ini.');
        }

        $stages = ['Pemilahan' => 1, 'Pendataan' => 2, 'Pelabelan' => 3, 'Input E-Arsip' => 4];
        $currentWeight = $stages[$monitoring->tahapan] ?? 0;

        if ($monitoring->tahapan == 'Pelabelan') {
            $monitoring->status_kerja = 'Menunggu E-Arsip';
            $monitoring->save();
            return redirect()->back()->with('success', 'Tahapan Pelabelan selesai. Menunggu staf E-Arsip menyelesaikan tugasnya.');
        }

        if ($currentWeight > 0 && $currentWeight < 3) {
            $rekanTertinggal = LogAktivitas::where('arsip_masuk_id', $monitoring->arsip_masuk_id)
                ->whereIn('tahapan', ['Pemilahan', 'Pendataan', 'Pelabelan'])
                ->where('id', '!=', $monitoring->id)
                ->get()
                ->filter(function($peer) use ($stages, $currentWeight) {
                    return ($stages[$peer->tahapan] ?? 0) < $currentWeight;
                });

            if ($rekanTertinggal->count() > 0) {
                return redirect()->back()->with('error', 'Gagal lanjut! Masih ada rekan kerja Anda di Berita Acara yang sama tertinggal di tahapan sebelumnya.');
            }

            $nextStageName = array_search($currentWeight + 1, $stages);

            // Simpan Riwayat
            RiwayatMonitoring::create([
                'log_aktivitas_id' => $monitoring->id,
                'user_id' => Auth::id(),
                'tahapan' => $nextStageName,
                'tanggal_kerja' => $monitoring->tanggal_kerja,
                'jumlah_box_selesai' => 0,
                'jumlah_tambahan' => 0,
                'keterangan' => 'Memulai tahapan ' . $nextStageName,
            ]);

            $monitoring->tahapan = $nextStageName;
            $monitoring->jumlah_box_selesai = 0; // Reset ke 0 untuk dikerjakan di tahap baru
            $monitoring->save();

            return redirect()->back()->with('success', 'Tahapan berhasil dilanjutkan ke ' . $nextStageName);
        }

        if ($monitoring->tahapan == 'Input E-Arsip') {
            if ($monitoring->status_kerja != 'Selesai') {
                $monitoring->status_kerja = 'Selesai';
                $monitoring->save();

                LogAktivitas::where('arsip_masuk_id', $monitoring->arsip_masuk_id)
                    ->update(['status_kerja' => 'Selesai']);

                return redirect()->back()->with('success', 'Input E-Arsip selesai! Seluruh aktivitas tim pada Berita Acara ini otomatis ditandai SELESAI.');
            }
        }

        return redirect()->back();
    }

    public function history(string $id)
    {
        $monitoring = LogAktivitas::findOrFail($id);
        $histories = $monitoring->riwayat()->with('user')->orderBy('created_at', 'desc')->get();
        return response()->json($histories);
    }

    public function addProgress(Request $request, string $id)
    {
        $monitoring = LogAktivitas::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() != $monitoring->user_id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak untuk menambah progress tugas staf lain.'], 403);
        }

        if ($monitoring->status_kerja == 'Selesai' || $monitoring->status_kerja == 'Menunggu E-Arsip') {
            return response()->json(['success' => false, 'message' => 'Data sudah terkunci, tidak bisa menambah progress.'], 400);
        }

        $request->validate([
            'jumlah_tambahan' => 'required|integer|min:1',
            'tanggal_baru' => 'required|date',
        ]);

        if ($monitoring->tahapan != 'Alih Media') {
            $tahapanGroup = in_array($monitoring->tahapan, ['Pemilahan', 'Pendataan', 'Pelabelan'])
                ? ['Pemilahan', 'Pendataan', 'Pelabelan'] : [$monitoring->tahapan];

            $totalSedangDikerjakanLainnya = LogAktivitas::where('arsip_masuk_id', $monitoring->arsip_masuk_id)
                                        ->whereIn('tahapan', $tahapanGroup)
                                        ->where('id', '!=', $monitoring->id)
                                        ->sum('jumlah_box_selesai');

            $harapanTotalBaru = $totalSedangDikerjakanLainnya + $monitoring->jumlah_box_selesai + $request->jumlah_tambahan;

            if ($harapanTotalBaru > $monitoring->jumlah_box) {
                $sisa = $monitoring->jumlah_box - ($totalSedangDikerjakanLainnya + $monitoring->jumlah_box_selesai);
                return response()->json([
                    'success' => false,
                    'message' => "Melebihi batas! Maksimal box yang tersisa untuk dikerjakan adalah " . $sisa . " Box."
                ], 400);
            }
        }

        RiwayatMonitoring::create([
            'log_aktivitas_id' => $monitoring->id,
            'user_id' => Auth::id(),
            'tahapan' => $monitoring->tahapan,
            'tanggal_kerja' => $request->tanggal_baru,
            'jumlah_box_selesai' => $monitoring->jumlah_box_selesai + $request->jumlah_tambahan,
            'jumlah_tambahan' => $request->jumlah_tambahan,
            'keterangan' => 'Penambahan progress',
        ]);

        $monitoring->jumlah_box_selesai += $request->jumlah_tambahan;
        $monitoring->tanggal_kerja = $request->tanggal_baru;
        $monitoring->save();

        return response()->json(['success' => true, 'message' => 'Progress berhasil ditambahkan!']);
    }
}
