<?php

namespace App\Http\Controllers;

use App\Models\ArsipMasuk;
use App\Models\User;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ArsipMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = ArsipMasuk::with('penerima')
            ->withCount('logAktivitas')
            ->withExists(['logAktivitas as is_completed' => function ($query) {
                $query->where('tahapan', 'Input E-Arsip');
            }])
            ->orderBy('id', 'desc');

        // PERBAIKAN: KEMBALI MENGGUNAKAN 'LIKE' UNTUK KODE BERSIMBOL
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('unit_asal', 'like', "%{$search}%")
                  ->orWhere('nomor_berita_acara', 'like', "%{$search}%")
                  ->orWhereHas('penerima', function($userQuery) use ($search) {
                      $userQuery->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('unit_asal')) $query->where('unit_asal', $request->unit_asal);
        if ($request->filled('penerima')) $query->where('user_penerima', $request->penerima);
        if ($request->filled('year')) $query->whereYear('tanggal_terima', $request->year);

        $arsipMasuk = $query->paginate(20)->withQueryString();

        $unitAsalOptions = ArsipMasuk::select('unit_asal')->distinct()->pluck('unit_asal');
        $yearOptions = ArsipMasuk::selectRaw('YEAR(tanggal_terima) as year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $users = User::all();

        return view('arsip-masuk.index', compact('arsipMasuk', 'unitAsalOptions', 'yearOptions', 'users'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type');
        $ids = json_decode($request->input('ids'), true);
        $search = $request->input('search');
        $unit_asal = $request->input('unit_asal');
        $year = $request->input('year');
        $penerima = $request->input('penerima');

        $export = new \App\Exports\ArsipMasukExport($ids, $search, $unit_asal, $year, $penerima);
        $filename = 'arsip-masuk-' . date('Y-m-d') . ($type === 'pdf' ? '.pdf' : '.xlsx');

        if ($type === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
        }

        if ($type === 'print') {
            $query = $export->query();
            $data = $query->get();
            $isPdf = false;

            return view('arsip-masuk.pdf', compact('data', 'isPdf'));
        }

        return redirect()->back();
    }

    public function create()
    {
        $users = User::where('is_active', true)->get();
        $units = \App\Models\Unit::where('is_active', true)->orderBy('nama_unit', 'asc')->get();
        return view('arsip-masuk.create', compact('users', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_asal' => 'required|string|max:255',
            'nomor_berita_acara' => 'required|string|max:100',
            'tanggal_terima' => 'required|date',
            'jumlah_box_masuk' => 'required|integer',
            'user_penerima' => 'required|exists:users,id',
        ]);

        ArsipMasuk::create($request->all());

        return redirect()->route('arsip-masuk.index')->with('success', 'Data Arsip Masuk berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $arsipMasuk = ArsipMasuk::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() != $arsipMasuk->user_penerima) {
            abort(403, 'Unauthorized action.');
        }

        // CEK APAKAH SUDAH MENCAPAI E-ARSIP
        $isCompleted = LogAktivitas::where('arsip_masuk_id', $id)->where('tahapan', 'Input E-Arsip')->exists();
        if ($isCompleted) {
            return redirect()->back()->with('error', 'Gagal! Data ini sudah mencapai tahapan E-Arsip di menu Monitoring sehingga tidak dapat diedit lagi.');
        }

        $users = User::where('is_active', true)
                     ->orWhere('id', $arsipMasuk->user_penerima)
                     ->get();
        $units = \App\Models\Unit::where('is_active', true)->orderBy('nama_unit', 'asc')->get();
        return view('arsip-masuk.edit', compact('arsipMasuk', 'users', 'units'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'unit_asal' => 'required|string|max:255',
            'nomor_berita_acara' => 'required|string|max:100',
            'tanggal_terima' => 'required|date',
            'jumlah_box_masuk' => 'required|integer',
            'user_penerima' => 'required|exists:users,id',
        ]);

        $arsipMasuk = ArsipMasuk::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() != $arsipMasuk->user_penerima) {
            abort(403, 'Unauthorized action.');
        }

        $isCompleted = LogAktivitas::where('arsip_masuk_id', $id)->where('tahapan', 'Input E-Arsip')->exists();
        if ($isCompleted) {
            return redirect()->back()->with('error', 'Gagal! Data ini sudah mencapai tahapan E-Arsip di menu Monitoring sehingga tidak dapat diedit lagi.');
        }

        $arsipMasuk->update($request->all());

        // PERBAIKAN: SINKRONISASI PERUBAHAN KE TABEL MONITORING (LOG AKTIVITAS)
        LogAktivitas::where('arsip_masuk_id', $arsipMasuk->id)->update([
            'nba' => $request->nomor_berita_acara,
            'unit_kerja' => $request->unit_asal
        ]);

        return redirect()->route('arsip-masuk.index')->with('success', 'Data Arsip Masuk berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $arsipMasuk = ArsipMasuk::findOrFail($id);

        if (Auth::user()->role !== 'admin' && Auth::id() != $arsipMasuk->user_penerima) {
            abort(403, 'Unauthorized action.');
        }

        $isUsedInMonitoring = LogAktivitas::where('arsip_masuk_id', $id)->exists();
        if ($isUsedInMonitoring) {
            return redirect()->back()->with('error', 'Gagal dihapus! Data Arsip Masuk ini sudah terhubung dan sedang dikerjakan pada menu Monitoring Kinerja.');
        }

        $arsipMasuk->delete();

        return redirect()->route('arsip-masuk.index')->with('success', 'Data Arsip Masuk berhasil dihapus.');
    }
}
