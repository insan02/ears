<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\RiwayatMonitoring;
use App\Models\User;
use App\Models\ArsipMasuk;
use App\Models\BerkasArsipMasuk;
use Illuminate\Http\Request;

class MonitoringKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd('Controller OK');
        $query = LogAktivitas::with('user')->orderBy('id', 'desc');

        // Search Filter
        if ($request->has('search') && $request->search != '') {
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

        // PIC Filter
        if ($request->has('pic') && $request->pic != '') {
            $query->where('user_id', $request->pic);
        }

        // Tahapan Filter
        if ($request->has('tahapan') && $request->tahapan != '') {
            $query->where('tahapan', $request->tahapan);
        }

        $monitoring = $query->get();
        $users = User::all(); // Fetch users for dropdown
        
        // Cards Data
        $total = ArsipMasuk::count();
        $bulanIni = ArsipMasuk::whereMonth('tanggal_terima', now()->month)
            ->whereYear('tanggal_terima', now()->year)
            ->count();
            
        $pemilahan = LogAktivitas::where('tahapan', 'Pemilahan')->count();
        $pendataan = LogAktivitas::where('tahapan', 'Pendataan')->count();
        $pelabelan = LogAktivitas::where('tahapan', 'Pelabelan')->count();
        $alihMedia = LogAktivitas::where('tahapan', 'Alih Media')->count();
        $inputEArsip = LogAktivitas::where('tahapan', 'Input E-Arsip')->count();
        
        return view('monitoring.index', compact('monitoring', 'total', 'bulanIni', 'pemilahan', 'pendataan', 'pelabelan', 'alihMedia', 'inputEArsip', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $arsipMasuk = ArsipMasuk::all(); // Get all ArsipMasuk for dropdown
        return view('monitoring.create', compact('users', 'arsipMasuk'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'tahapan' => 'required|string|in:Pemilahan,Pendataan,Pelabelan,Alih Media,Input E-Arsip',
            'jumlah_box_selesai' => 'nullable|integer',
            'tanggal_kerja' => 'required|date',
            'keterangan' => 'nullable|string',
        ];

        if ($request->tahapan != 'Alih Media') {
            $rules['arsip_masuk_id'] = 'required|exists:arsip_masuk,id';
        } else {
            $rules['arsip_masuk_id'] = 'nullable';
        }

        $request->validate($rules);

        if ($request->tahapan != 'Alih Media') {
            $arsipMasuk = ArsipMasuk::findOrFail($request->arsip_masuk_id);
            $nba = $arsipMasuk->nomor_berita_acara;
            $jumlahBox = $arsipMasuk->jumlah_box_masuk;
            $unitKerja = $arsipMasuk->unit_asal;
            $arsipMasukId = $arsipMasuk->id;
        } else {
            $nba = null;
            $jumlahBox = 0; // Or null if allowed
            $unitKerja = '-'; // Default if not linked
            $arsipMasukId = null;
        }

        LogAktivitas::create([
            'user_id' => $request->user_id,
            'arsip_masuk_id' => $arsipMasukId,
            'nba' => $nba,
            'tahapan' => $request->tahapan,
            'jumlah_box' => $jumlahBox,
            'jumlah_box_selesai' => $request->jumlah_box_selesai ?? 0,
            'tanggal_kerja' => $request->tanggal_kerja,
            'unit_kerja' => $unitKerja,
            'keterangan' => $request->keterangan,
            'status_kerja' => 'Proses', // Default status
        ]);
        
        return redirect()->route('monitoring.index')->with('success', 'Data Monitoring berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $monitoring = LogAktivitas::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $monitoring->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $users = User::all();
        $arsipMasuk = ArsipMasuk::all();
        return view('monitoring.edit', compact('monitoring', 'users', 'arsipMasuk'));
    }

    public function update(Request $request, $id)
    {
        $logAktivitas = LogAktivitas::findOrFail($id);
        
        if (auth()->user()->role !== 'admin' && auth()->id() !== $logAktivitas->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent editing if status is Selesai
        if ($logAktivitas->status_kerja == 'Selesai') {
            return redirect()->back()->with('error', 'Data yang sudah selesai tidak dapat diedit!');
        }

        $rules = [
            'user_id' => 'required|exists:users,id',
            'tahapan' => 'required|string|in:Pemilahan,Pendataan,Pelabelan,Alih Media,Input E-Arsip',
            'jumlah_box_selesai' => 'nullable|integer',
            'tanggal_kerja' => 'required|date',
            'keterangan' => 'nullable|string',
        ];

        if ($request->tahapan != 'Alih Media') {
            $rules['arsip_masuk_id'] = 'required|exists:arsip_masuk,id';
        } else {
            $rules['arsip_masuk_id'] = 'nullable';
        }

        $request->validate($rules);

        if ($request->tahapan != 'Alih Media') {
            $arsipMasuk = ArsipMasuk::findOrFail($request->arsip_masuk_id);
            $nba = $arsipMasuk->nomor_berita_acara;
            $jumlahBox = $arsipMasuk->jumlah_box_masuk;
            $unitKerja = $arsipMasuk->unit_asal;
            $arsipMasukId = $arsipMasuk->id;
        } else {
            $nba = null;
            $jumlahBox = 0;
            $unitKerja = '-';
            $arsipMasukId = null;
        }

        // Save history before updating
        RiwayatMonitoring::create([
            'log_aktivitas_id' => $logAktivitas->id,
            'user_id' => auth()->id(), // User who performs the edit
            'tahapan' => $logAktivitas->tahapan,
            'tanggal_kerja' => $logAktivitas->tanggal_kerja,
            'jumlah_box_selesai' => $logAktivitas->jumlah_box_selesai,
            'keterangan' => $logAktivitas->keterangan,
        ]);
    
        $logAktivitas->update([
            'user_id' => $request->user_id,
            'arsip_masuk_id' => $arsipMasukId,
            'nba' => $nba,
            'tahapan' => $request->tahapan,
            'jumlah_box' => $jumlahBox,
            'jumlah_box_selesai' => $request->jumlah_box_selesai ?? 0,
            'tanggal_kerja' => $request->tanggal_kerja,
            'unit_kerja' => $unitKerja,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('monitoring.index')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $logAktivitas = LogAktivitas::findOrFail($id);
        
        if (auth()->user()->role !== 'admin' && auth()->id() !== $logAktivitas->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent deleting if status is Selesai
        if ($logAktivitas->status_kerja == 'Selesai') {
            return redirect()->back()->with('error', 'Data yang sudah selesai tidak dapat dihapus!');
        }
        
        $logAktivitas->delete();
        return redirect()->route('monitoring.index')->with('success', 'Data berhasil dihapus!');
    }

    /**
     * Advance the stage of the specified resource.
     */
    public function advanceStage($id)
    {
        $monitoring = LogAktivitas::findOrFail($id);
        $stages = ['Pemilahan', 'Pendataan', 'Pelabelan', 'Input E-Arsip'];
        
        $currentStageIndex = array_search($monitoring->tahapan, $stages);
        
        // Handle transitions between normal stages
        if ($currentStageIndex !== false && $currentStageIndex < count($stages) - 1) {
            $nextStage = $stages[$currentStageIndex + 1];

            // Save history before advancing
            RiwayatMonitoring::create([
                'log_aktivitas_id' => $monitoring->id,
                'user_id' => auth()->id(),
                'tahapan' => $monitoring->tahapan,
                'tanggal_kerja' => $monitoring->tanggal_kerja,
                'jumlah_box_selesai' => $monitoring->jumlah_box_selesai,
                'keterangan' => $monitoring->keterangan,
            ]);

            $monitoring->tahapan = $nextStage;
            $monitoring->save();
            return redirect()->back()->with('success', 'Tahapan berhasil dilanjutkan ke ' . $nextStage);
        }
        
        // Handle completion of the final stage (Input E-Arsip)
        if ($monitoring->tahapan == 'Input E-Arsip') {
            if ($monitoring->status_kerja != 'Selesai') {
                $monitoring->status_kerja = 'Selesai';
                // Automatically set jumlah_box_selesai to max to reflect 100% progress
                $monitoring->jumlah_box_selesai = $monitoring->jumlah_box; 
                $monitoring->save();
                return redirect()->back()->with('success', 'Tahapan Input E-Arsip selesai! Aktivitas ditandai COMPLETED.');
            }
        }

        return redirect()->back()->with('info', 'Tahapan sudah mencapai batas atau sudah selesai.');
    }

    public function history($id)
    {
        $monitoring = LogAktivitas::findOrFail($id);
        $histories = $monitoring->riwayat()->with('user')->orderBy('created_at', 'desc')->get();

        return response()->json($histories);
    }

    public function addProgress(Request $request, $id)
    {
        $monitoring = LogAktivitas::findOrFail($id);

        if ($monitoring->status_kerja == 'Selesai') {
            return response()->json(['success' => false, 'message' => 'Data sudah selesai, tidak bisa menambah progress.'], 400);
        }

        $request->validate([
            'jumlah_tambahan' => 'required|integer|min:1',
            'tanggal_baru' => 'required|date',
        ]);

        // Save history first
        RiwayatMonitoring::create([
            'log_aktivitas_id' => $monitoring->id,
            'user_id' => auth()->id(),
            'tahapan' => $monitoring->tahapan,
            'tanggal_kerja' => $monitoring->tanggal_kerja,
            'jumlah_box_selesai' => $monitoring->jumlah_box_selesai,
            'jumlah_tambahan' => $request->jumlah_tambahan,
            'keterangan' => 'Menambah ' . $request->jumlah_tambahan . ' box',
        ]);

        // Update with new cumulative values
        $monitoring->jumlah_box_selesai += $request->jumlah_tambahan;
        $monitoring->tanggal_kerja = $request->tanggal_baru;
        $monitoring->save();

        return response()->json(['success' => true, 'message' => 'Progress berhasil ditambahkan!']);
    }
}