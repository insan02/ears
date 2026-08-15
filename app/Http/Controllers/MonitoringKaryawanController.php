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
    /**
     * Fungsi dinamis pengecek batas (limit) gabungan tim per Berita Acara
     * @param int $arsipMasukId ID Arsip
     * @param string $tahapan Tahapan saat ini
     * @param int $tambahan Jumlah box/lembar yang BARU SAJA mau ditambahkan
     */
    private function checkLimit($arsipMasukId, $tahapan, $tambahan) {
        if (!$arsipMasukId) return null;

        $arsip = ArsipMasuk::find($arsipMasukId);
        if (!$arsip) return null;

        // Ambil total SAAT INI (gabungan semua staf) di tahapan ini
        $currentTotalTeam = LogAktivitas::where('arsip_masuk_id', $arsipMasukId)
                                        ->where('tahapan', $tahapan)
                                        ->sum('jumlah_box_selesai');
                                        
        // Prediksi total jika penambahan ini diizinkan
        $grandTotalHarapan = $currentTotalTeam + $tambahan;

        // Aturan 1: Pemilahan -> Maksimal dari jumlah box awal Arsip Masuk
        if ($tahapan == 'Pemilahan') {
            $max = $arsip->jumlah_box_masuk;
            if ($grandTotalHarapan > $max) {
                $sisa = max(0, $max - $currentTotalTeam);
                return "Gagal! Maksimal Pemilahan adalah {$max} Box. Sisa kuota gabungan tim saat ini: {$sisa} Box.";
            }
        }

        // Aturan 2: Pendataan -> Bebas (Bisa bertambah/berkurang) -> Tidak ada limit

        // Aturan 3: Pelabelan -> Maksimal dari total box hasil Pendataan
        if ($tahapan == 'Pelabelan') {
            $max = LogAktivitas::where('arsip_masuk_id', $arsipMasukId)->where('tahapan', 'Pendataan')->sum('jumlah_box_selesai');
            if ($max == 0) return "Belum ada progress box di tahap Pendataan.";
            
            if ($grandTotalHarapan > $max) {
                $sisa = max(0, $max - $currentTotalTeam);
                return "Gagal! Maksimal Pelabelan menyesuaikan total Pendataan ({$max} Box). Sisa kuota: {$sisa} Box.";
            }
        }

        // Aturan 4: Alih Media -> Satuannya Lembar (Bebas) -> Tidak ada limit

        // Aturan 5: Input E-Arsip -> Maksimal dari total box hasil Pelabelan
        if ($tahapan == 'Input E-Arsip') {
            $max = LogAktivitas::where('arsip_masuk_id', $arsipMasukId)->where('tahapan', 'Pelabelan')->sum('jumlah_box_selesai');
            if ($max == 0) return "Belum ada progress box di tahap Pelabelan.";
            
            if ($grandTotalHarapan > $max) {
                $sisa = max(0, $max - $currentTotalTeam);
                return "Gagal! Maksimal E-Arsip menyesuaikan total Pelabelan ({$max} Box). Sisa kuota: {$sisa} Box.";
            }
        }

        return null; // Lolos Validasi
    }

    public function index(Request $request)
    {
        $query = LogAktivitas::with('user')->orderBy('arsip_masuk_id', 'desc')->orderBy('tahapan', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('tahapan', 'like', "%{$search}%")
                ->orWhere('unit_kerja', 'like', "%{$search}%")
                ->orWhere('nba', 'like', "%{$search}%");
            });
        }

        if ($request->filled('pic')) $query->where('user_id', $request->pic);
        if ($request->filled('tahapan')) $query->where('tahapan', $request->tahapan);

        $monitoring = $query->paginate(20)->withQueryString();
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
        
        // Hanya ambil Arsip yang BELUM selesai 100%
        $arsipMasuk = ArsipMasuk::with('logAktivitas')->whereDoesntHave('logAktivitas', function($q) {
            $q->where('tahapan', 'Input E-Arsip')->where('status_kerja', 'Selesai');
        })->get();

        // Hitung status setiap tahapan untuk dikirim ke Javascript/Alpine
        $arsipStatus = [];
        foreach ($arsipMasuk as $arsip) {
            $pemilahan = $arsip->logAktivitas->where('tahapan', 'Pemilahan')->sum('jumlah_box_selesai');
            $pendataan = $arsip->logAktivitas->where('tahapan', 'Pendataan')->sum('jumlah_box_selesai');
            $pelabelan = $arsip->logAktivitas->where('tahapan', 'Pelabelan')->sum('jumlah_box_selesai');
            $earship   = $arsip->logAktivitas->where('tahapan', 'Input E-Arsip')->sum('jumlah_box_selesai');

            $arsipStatus[$arsip->id] = [
                'Pemilahan' => $pemilahan >= $arsip->jumlah_box_masuk,
                'Pendataan' => false, // Bebas
                'Pelabelan' => ($pendataan == 0) || ($pelabelan >= $pendataan),
                'Alih Media' => ($pelabelan == 0), // Baru bisa klik jika pelabelan sudah ada
                'Input E-Arsip' => ($pelabelan == 0) || ($earship >= $pelabelan),
            ];
        }

        return view('monitoring.create', compact('users', 'arsipMasuk', 'arsipStatus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tahapan' => 'required|string|in:Pemilahan,Pendataan,Pelabelan,Alih Media,Input E-Arsip',
            'jumlah_box_selesai' => 'nullable|integer|min:0',
            'tanggal_kerja' => 'required|date',
            'arsip_masuk_id' => 'required|exists:arsip_masuk,id'
        ]);

        $arsipMasuk = ArsipMasuk::findOrFail($request->arsip_masuk_id);
        $jumlahSelesai = $request->jumlah_box_selesai ?? 0;

        // CEK LIMIT MENGGUNAKAN HELPER
        $limitError = $this->checkLimit($arsipMasuk->id, $request->tahapan, $jumlahSelesai);
        if ($limitError) return back()->withErrors(['jumlah_box_selesai' => $limitError]);

        $log = LogAktivitas::create([
            'user_id' => $request->user_id,
            'arsip_masuk_id' => $arsipMasuk->id,
            'nba' => $arsipMasuk->nomor_berita_acara,
            'tahapan' => $request->tahapan,
            'jumlah_box' => $arsipMasuk->jumlah_box_masuk,
            'jumlah_box_selesai' => $jumlahSelesai,
            'tanggal_kerja' => $request->tanggal_kerja,
            'unit_kerja' => $arsipMasuk->unit_asal,
            'keterangan' => $request->keterangan,
            'status_kerja' => 'Proses',
        ]);

        RiwayatMonitoring::create([
            'log_aktivitas_id' => $log->id,
            'user_id' => Auth::id(),
            'tahapan' => $log->tahapan,
            'tanggal_kerja' => $log->tanggal_kerja,
            'jumlah_box_selesai' => $log->jumlah_box_selesai,
            'jumlah_tambahan' => $jumlahSelesai,
            'keterangan' => 'Memulai pekerjaan',
        ]);
        
        return redirect()->route('monitoring.index')->with('success', 'Tugas berhasil ditambahkan!');
    }

    public function edit(string $id)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Hanya Admin yang dapat mengedit data ini.');
        
        $monitoring = LogAktivitas::findOrFail($id);
        $users = User::all();
        
        // Ambil arsip yang belum selesai ATAU arsip tempat monitoring ini berada (untuk jaga-jaga edit data)
        $arsipMasuk = ArsipMasuk::with('logAktivitas')->whereDoesntHave('logAktivitas', function($q) {
            $q->where('tahapan', 'Input E-Arsip')->where('status_kerja', 'Selesai');
        })->orWhere('id', $monitoring->arsip_masuk_id)->get();

        $arsipStatus = [];
        foreach ($arsipMasuk as $arsip) {
            // Hitung tanpa memasukkan nilai yang sedang diedit ini agar tidak mengunci dirinya sendiri
            $pemilahan = $arsip->logAktivitas->where('tahapan', 'Pemilahan')->where('id', '!=', $id)->sum('jumlah_box_selesai');
            $pendataan = $arsip->logAktivitas->where('tahapan', 'Pendataan')->where('id', '!=', $id)->sum('jumlah_box_selesai');
            $pelabelan = $arsip->logAktivitas->where('tahapan', 'Pelabelan')->where('id', '!=', $id)->sum('jumlah_box_selesai');
            $earship   = $arsip->logAktivitas->where('tahapan', 'Input E-Arsip')->where('id', '!=', $id)->sum('jumlah_box_selesai');

            $totalPendataan = $arsip->logAktivitas->where('tahapan', 'Pendataan')->sum('jumlah_box_selesai');
            $totalPelabelan = $arsip->logAktivitas->where('tahapan', 'Pelabelan')->sum('jumlah_box_selesai');

            $arsipStatus[$arsip->id] = [
                'Pemilahan' => $pemilahan >= $arsip->jumlah_box_masuk,
                'Pendataan' => false,
                'Pelabelan' => ($totalPendataan == 0) || ($pelabelan >= $totalPendataan),
                'Alih Media' => ($totalPelabelan == 0),
                'Input E-Arsip' => ($totalPelabelan == 0) || ($earship >= $totalPelabelan),
            ];
        }

        return view('monitoring.edit', compact('monitoring', 'users', 'arsipMasuk', 'arsipStatus'));
    }

    public function update(Request $request, string $id)
    {
        if (Auth::user()->role !== 'admin') abort(403, 'Hanya Admin yang dapat mengedit data ini.');
        $logAktivitas = LogAktivitas::findOrFail($id);

        if ($logAktivitas->status_kerja == 'Selesai') {
            return redirect()->back()->with('error', 'Data yang sudah selesai tidak dapat diedit!');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tahapan' => 'required|string|in:Pemilahan,Pendataan,Pelabelan,Alih Media,Input E-Arsip',
            'jumlah_box_selesai' => 'nullable|integer|min:0',
            'tanggal_kerja' => 'required|date',
            'arsip_masuk_id' => 'required|exists:arsip_masuk,id'
        ]);

        $arsipMasuk = ArsipMasuk::findOrFail($request->arsip_masuk_id);
        $jumlahSelesai = $request->jumlah_box_selesai ?? 0;

        // Hitung Tambahan/Selisih Edit
        // Jika BA / Tahapan berubah, anggap sebagai input baru (100% tambahan). Jika sama, hitung selisihnya.
        $isSameGroup = ($logAktivitas->tahapan == $request->tahapan && $logAktivitas->arsip_masuk_id == $arsipMasuk->id);
        $tambahan = $isSameGroup ? ($jumlahSelesai - $logAktivitas->jumlah_box_selesai) : $jumlahSelesai;

        // CEK LIMIT MENGGUNAKAN HELPER
        $limitError = $this->checkLimit($arsipMasuk->id, $request->tahapan, $tambahan);
        if ($limitError) return back()->withErrors(['jumlah_box_selesai' => $limitError]);

        RiwayatMonitoring::create([
            'log_aktivitas_id' => $logAktivitas->id,
            'user_id' => Auth::id(), 
            'tahapan' => $request->tahapan,
            'tanggal_kerja' => $request->tanggal_kerja,
            'jumlah_box_selesai' => $jumlahSelesai,
            'jumlah_tambahan' => $tambahan,
            'keterangan' => 'Admin mengedit data',
        ]);
    
        $logAktivitas->update([
            'user_id' => $request->user_id,
            'arsip_masuk_id' => $arsipMasuk->id,
            'nba' => $arsipMasuk->nomor_berita_acara,
            'tahapan' => $request->tahapan,
            'jumlah_box' => $arsipMasuk->jumlah_box_masuk,
            'jumlah_box_selesai' => $jumlahSelesai,
            'tanggal_kerja' => $request->tanggal_kerja,
            'unit_kerja' => $arsipMasuk->unit_asal,
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
            return redirect()->back()->with('error', 'Anda tidak berhak memajukan tahapan tugas staf lain.');
        }

        $stages = ['Pemilahan' => 1, 'Pendataan' => 2, 'Pelabelan' => 3, 'Alih Media' => 4, 'Input E-Arsip' => 5];
        $currentWeight = $stages[$monitoring->tahapan] ?? 0;

        // Pindah dari Pemilahan -> Pendataan, atau Pendataan -> Pelabelan
        if (in_array($monitoring->tahapan, ['Pemilahan', 'Pendataan'])) {
            $nextStageName = $monitoring->tahapan == 'Pemilahan' ? 'Pendataan' : 'Pelabelan';

            // Cek Rekan Tim yang tertinggal (Sync System)
            $rekanTertinggal = LogAktivitas::where('arsip_masuk_id', $monitoring->arsip_masuk_id)
                ->whereNotIn('status_kerja', ['Selesai', 'Menunggu Alih Media', 'Menunggu E-Arsip'])
                ->where('id', '!=', $monitoring->id)
                ->get()
                ->filter(function($peer) use ($stages, $currentWeight) {
                    return ($stages[$peer->tahapan] ?? 0) < $currentWeight; 
                });

            if ($rekanTertinggal->count() > 0) {
                return redirect()->back()->with('error', 'Gagal lanjut! Masih ada rekan kerja Anda di BA ini yang tertinggal di tahapan sebelumnya.');
            }

            // Kunci Log saat ini jadi 'Selesai'
            $monitoring->status_kerja = 'Selesai';
            $monitoring->save();

            // BUAT LOG BARU UNTUK TAHAP SELANJUTNYA
            $newLog = LogAktivitas::create([
                'user_id' => $monitoring->user_id,
                'arsip_masuk_id' => $monitoring->arsip_masuk_id,
                'nba' => $monitoring->nba,
                'tahapan' => $nextStageName,
                'jumlah_box' => $monitoring->jumlah_box,
                'jumlah_box_selesai' => 0,
                'tanggal_kerja' => now(),
                'unit_kerja' => $monitoring->unit_kerja,
                'keterangan' => 'Lanjutan dari tahap ' . $monitoring->tahapan,
                'status_kerja' => 'Proses',
            ]);

            RiwayatMonitoring::create([
                'log_aktivitas_id' => $newLog->id,
                'user_id' => Auth::id(),
                'tahapan' => $nextStageName,
                'tanggal_kerja' => now(),
                'jumlah_box_selesai' => 0,
                'jumlah_tambahan' => 0,
                'keterangan' => 'Memulai tahapan baru',
            ]);

            return redirect()->back()->with('success', 'Tahapan berhasil dilanjutkan ke ' . $nextStageName);
        }

        // Kunci di Pelabelan
        if ($monitoring->tahapan == 'Pelabelan') {
            $monitoring->status_kerja = 'Menunggu Alih Media';
            $monitoring->save();
            return redirect()->back()->with('success', 'Tahapan Pelabelan selesai. Data dikunci menunggu Alih Media.');
        }

        // Kunci di Alih Media
        if ($monitoring->tahapan == 'Alih Media') {
            $monitoring->status_kerja = 'Menunggu E-Arsip';
            $monitoring->save();
            return redirect()->back()->with('success', 'Tahapan Alih Media selesai. Data dikunci menunggu Input E-Arsip.');
        }

        // Penyelesaian Final oleh E-Arsip
        if ($monitoring->tahapan == 'Input E-Arsip') {
            if ($monitoring->status_kerja != 'Selesai') {
                $monitoring->status_kerja = 'Selesai';
                $monitoring->save();

                // OTOMATIS SELESAIKAN SEMUA TASK YANG 'MENUNGGU' DI BA INI
                LogAktivitas::where('arsip_masuk_id', $monitoring->arsip_masuk_id)
                    ->whereIn('status_kerja', ['Menunggu Alih Media', 'Menunggu E-Arsip'])
                    ->update(['status_kerja' => 'Selesai']);

                return redirect()->back()->with('success', 'E-Arsip selesai! Seluruh aktivitas tim pada BA ini otomatis ditandai SELESAI.');
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
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki hak menambah progress tugas staf lain.'], 403);
        }

        if (in_array($monitoring->status_kerja, ['Selesai', 'Menunggu Alih Media', 'Menunggu E-Arsip'])) {
            return response()->json(['success' => false, 'message' => 'Data sudah terkunci, tidak bisa menambah progress.'], 400);
        }

        $request->validate([
            'jumlah_tambahan' => 'required|integer|min:1',
            'tanggal_baru' => 'required|date',
        ]);

        $tambahan = $request->jumlah_tambahan;

        // CEK LIMIT MENGGUNAKAN HELPER
        $limitError = $this->checkLimit($monitoring->arsip_masuk_id, $monitoring->tahapan, $tambahan);
        if ($limitError) {
            return response()->json(['success' => false, 'message' => $limitError], 400);
        }

        RiwayatMonitoring::create([
            'log_aktivitas_id' => $monitoring->id,
            'user_id' => Auth::id(),
            'tahapan' => $monitoring->tahapan,
            'tanggal_kerja' => $request->tanggal_baru,
            'jumlah_box_selesai' => $monitoring->jumlah_box_selesai + $tambahan,
            'jumlah_tambahan' => $tambahan,
            'keterangan' => 'Penambahan progress',
        ]);

        $monitoring->jumlah_box_selesai += $tambahan;
        $monitoring->tanggal_kerja = $request->tanggal_baru;
        $monitoring->save();

        return response()->json(['success' => true, 'message' => 'Progress berhasil ditambahkan!']);
    }
}