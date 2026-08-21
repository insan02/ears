<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\ArsipMusnah;
use App\Models\MasterKlasifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArsipImport;
use Illuminate\Support\Facades\Cache;

class ArsipController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        // Ambil ID Import dari request AJAX
        $importId = $request->input('import_id');

        if (!in_array($extension, ['xls', 'xlsx', 'csv'])) {
            return response()->json(['success' => false, 'message' => 'Format file harus Excel (.xlsx) atau CSV.']);
        }

        try {
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', 0);

            // Inisiasi progress 0
            if ($importId) {
                Cache::put('import_arsip_progress_' . $importId, 1, 3600);
            }

            Excel::import(new ArsipImport($importId), $file);

            // Hapus cache setelah selesai
            if ($importId) {
                Cache::forget('import_arsip_progress_' . $importId);
            }

            // Set session flash message untuk reload halaman
            session()->flash('success', 'Data arsip berhasil diimport!');
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Import Exception: " . $e->getMessage());
            if ($importId) Cache::forget('import_arsip_progress_' . $importId);
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    // FUNGSI BARU UNTUK MEMBACA PROGRES
    public function checkProgress(Request $request)
    {
        $id = $request->input('id');
        $progress = Cache::get('import_arsip_progress_' . $id, 0);
        return response()->json(['processed' => $progress]);
    }

    public function showImportForm()
    {
        return view('arsip.import');
    }

    // GANTI HANYA FUNGSI index() DI DALAM ArsipController.php
    // GANTI HANYA FUNGSI index() DI DALAM ArsipController.php
    public function index(Request $request)
    {
        $query = Arsip::with(['klasifikasi']);

        // Search logic
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_berkas', 'like', "%{$search}%")
                  ->orWhere('no_berkas', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%")
                  ->orWhere('unit_pengolah', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%")
                  ->orWhere('jumlah', 'like', "%{$search}%")
                  ->orWhere('no_box', 'like', "%{$search}%")
                  ->orWhere('hak_akses', 'like', "%{$search}%")
                  ->orWhere('masa_simpan', 'like', "%{$search}%")
                  ->orWhere('jenis_media', 'like', "%{$search}%")
                  ->orWhere('tindakan_akhir', 'like', "%{$search}%");
            });
        }

        // PERBAIKAN: Menggunakan 'like' pada filter_status
        if ($request->filled('filter_status')) {
            $query->where('tindakan_akhir', 'like', '%' . $request->filter_status . '%');
        }

        if ($request->filled('filter_hak_akses')) $query->where('hak_akses', $request->filter_hak_akses);
        if ($request->filled('filter_tahun')) $query->where('tahun', $request->filter_tahun);
        if ($request->filled('filter_box')) $query->where('no_box', $request->filter_box);

        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('id', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('tahun', 'desc')->orderBy('id', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('tahun', 'asc')->orderBy('id', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        $printMode = $request->get('print') === 'true';

        if ($printMode) {
             $arsips = $query->get();
        } else {
             $arsips = $query->paginate(50);
        }

        $availableYears = Arsip::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $availableBoxes = Arsip::select('no_box')->whereNotNull('no_box')->where('no_box', '!=', '')->distinct()->orderByRaw('CAST(no_box AS UNSIGNED) ASC')->pluck('no_box');

        if ($request->ajax()) {
            return view('arsip.partials.table', compact('arsips'));
        }

        return view('arsip.arsip', compact('arsips', 'availableYears', 'availableBoxes', 'printMode'));
    }

    public function create()
    {
        $klasifikasis = MasterKlasifikasi::all();
        $units = \App\Models\Unit::all();
        $nextNumber = Arsip::distinct('no_berkas')->count() + 1;
        return view('arsip.input-arsip', compact('klasifikasis', 'nextNumber', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_berkas' => 'required|string',
            'isi_berkas' => 'required|array|min:1',
            'isi_berkas.*.isi' => 'required|string',
            'isi_berkas.*.unit_pengolah' => 'required|string',
            'isi_berkas.*.klasifikasi_id' => 'required|exists:master_klasifikasi,id',
            'isi_berkas.*.tahun' => 'nullable|integer',
            'isi_berkas.*.tanggal' => 'nullable|date',
            'isi_berkas.*.jumlah' => 'nullable|integer',
            'isi_berkas.*.no_box' => 'nullable|string',
            'isi_berkas.*.hak_akses' => 'nullable|string',
            'isi_berkas.*.jenis_media' => 'nullable|string',
            'isi_berkas.*.masa_simpan' => 'nullable|string',
            'isi_berkas.*.tindakan_akhir' => 'nullable|string',
        ]);

        $currentCount = Arsip::distinct('no_berkas')->count();
        $nextNo = $currentCount + 1;
        $no_berkas = (string) $nextNo;

        $user = \App\Models\User::first();

        foreach ($validated['isi_berkas'] as $item) {
            Arsip::create([
                'no_berkas'     => $no_berkas,
                'klasifikasi_id'=> $item['klasifikasi_id'],
                'nama_berkas'   => $validated['nama_berkas'],
                'unit_pengolah' => $item['unit_pengolah'],
                'user_id'       => $user ? $user->id : 1,
                'isi'           => $item['isi'],
                'tahun'         => $item['tahun'] ?? null,
                'tanggal_masuk' => $item['tanggal'] ?? null,
                'jumlah'        => $item['jumlah'] ?? 1,
                'no_box'        => $item['no_box'] ?? '-',
                'hak_akses'     => $item['hak_akses'] ?? '-',
                'jenis_media'   => $item['jenis_media'] ?? 'Kertas',
                'masa_simpan'   => $item['masa_simpan'] ?? '-',
                'tindakan_akhir'=> $item['tindakan_akhir'] ?? '-',
            ]);
        }

        return redirect('/arsip')->with('success', "Data arsip berhasil ditambahkan! Nomor Berkas: $no_berkas");
    }

    public function edit($id)
    {
        $arsip = Arsip::with('klasifikasi')->findOrFail($id);
        $nextNumber = $arsip->no_berkas;

        $initialData = [[
            'isi' => $arsip->isi,
            'tahun' => $arsip->tahun,
            'tanggal' => $arsip->tanggal_masuk,
            'jumlah' => $arsip->jumlah,
            'no_box' => $arsip->no_box,
            'hak_akses' => $arsip->hak_akses,
            'jenis_media' => $arsip->jenis_media,
            'masa_simpan' => $arsip->masa_simpan,
            'tindakan_akhir' => $arsip->tindakan_akhir,
            'unit_pengolah' => $arsip->unit_pengolah,
            'kode_klasifikasi' => $arsip->klasifikasi->kode_klasifikasi ?? '',
            'klasifikasi_id' => $arsip->klasifikasi_id,
        ]];

        $units = \App\Models\Unit::all();
        return view('arsip.input-arsip', compact('arsip', 'nextNumber', 'initialData', 'units'));
    }

    public function update(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);
        $validated = $request->validate([
            'nama_berkas' => 'required|string',
            'isi_berkas' => 'required|array|min:1',
            'isi_berkas.*.isi' => 'required|string',
            'isi_berkas.*.unit_pengolah' => 'required|string',
            'isi_berkas.*.klasifikasi_id' => 'required|exists:master_klasifikasi,id',
            'isi_berkas.*.tahun' => 'nullable|integer',
            'isi_berkas.*.tanggal' => 'nullable|date',
            'isi_berkas.*.jumlah' => 'nullable|integer',
            'isi_berkas.*.no_box' => 'nullable|string',
            'isi_berkas.*.hak_akses' => 'nullable|string',
            'isi_berkas.*.jenis_media' => 'nullable|string',
            'isi_berkas.*.masa_simpan' => 'nullable|string',
            'isi_berkas.*.tindakan_akhir' => 'nullable|string',
        ]);

        $item = $validated['isi_berkas'][0];

        $arsip->update([
            'nama_berkas'   => $validated['nama_berkas'],
            'klasifikasi_id'=> $item['klasifikasi_id'],
            'unit_pengolah' => $item['unit_pengolah'],
            'isi'           => $item['isi'],
            'tahun'         => $item['tahun'] ?? null,
            'tanggal_masuk' => $item['tanggal'] ?? null,
            'jumlah'        => $item['jumlah'] ?? 1,
            'no_box'        => $item['no_box'] ?? '-',
            'hak_akses'     => $item['hak_akses'] ?? '-',
            'jenis_media'   => $item['jenis_media'] ?? 'Kertas',
            'masa_simpan'   => $item['masa_simpan'] ?? '-',
            'tindakan_akhir'=> $item['tindakan_akhir'] ?? '-',
        ]);

        return redirect('/arsip')->with('success', 'Data arsip berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $arsip = Arsip::find($id);
        if (!$arsip) return redirect()->back()->with('error', 'Data tidak ditemukan');

        // PERBAIKAN: Menggunakan str_contains agar bisa mendeteksi "Musnah kecuali bla bla"
        if (str_contains(strtolower($arsip->tindakan_akhir ?? ''), 'musnah')) {
            try {
                DB::transaction(function () use ($arsip) {
                    $data = $arsip->toArray();
                    unset($data['id']);
                    $data['deleted_at'] = now();
                    ArsipMusnah::create($data);
                    $arsip->delete();
                });
                return redirect()->back()->with('success', 'Arsip berhasil dimusnahkan dan dipindahkan ke Data Musnah.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal memusnahkan arsip: ' . $e->getMessage());
            }
        } else {
             return redirect()->back()->with('error', 'Arsip tidak dapat dihapus karena status bukan Musnah.');
        }
    }

    public function musnah(Request $request)
    {
        $query = ArsipMusnah::with('klasifikasi');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_berkas', 'like', "%{$search}%")
                  ->orWhere('no_berkas', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%");
            });
        }

        $arsips = $query->orderBy('deleted_at', 'desc')->paginate(25)->withQueryString();
        return view('arsip.musnah', compact('arsips'));
    }

    public function export(Request $request)
    {
        $type = $request->input('type');
        $ids = json_decode($request->input('ids'), true);
        $search = $request->input('search');
        $sort = $request->input('sort');
        $filter_status = $request->input('filter_status');
        $filter_hak_akses = $request->input('filter_hak_akses');
        $filter_tahun = $request->input('filter_tahun');

        $export = new \App\Exports\ArsipExport($ids, $search, $sort, $filter_status, $filter_tahun, $filter_hak_akses);
        $filename = 'arsip-all-' . date('Y-m-d') . ($type === 'pdf' ? '.pdf' : '.xlsx');

        if ($type === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
        }

        return redirect()->back();
    }

    public function getKlasifikasiOptions(Request $request)
    {
        $level = $request->input('level', 0);
        $parent = $request->input('parent');

        if ($level == 1) {
            $query = MasterKlasifikasi::select('kode_klasifikasi');
            $codes = $query->get();
            $unique = $codes->map(function($item) {
                $parts = explode('.', $item->kode_klasifikasi);
                return isset($parts[0]) ? $parts[0] : null;
            })->unique()->filter()->values();

            $formatted = $unique->map(function($code) {
                return [
                    'code' => $code,
                    'label' => $this->getKategoriLabel($code)
                ];
            });
            return response()->json($formatted);
        }

        if ($level == 2 && $parent) {
            $codes = MasterKlasifikasi::where('kode_klasifikasi', 'like', $parent . '.%')->get();
            $unique = $codes->map(function($item) {
                $parts = explode('.', $item->kode_klasifikasi);
                return isset($parts[0], $parts[1]) ? $parts[0] . '.' . $parts[1] : null;
            })->unique()->filter()->values();

            $formatted = $unique->map(function($code) {
                return [
                    'code' => $code,
                    'label' => $this->getSubKategoriLabel($code)
                ];
            });
            return response()->json($formatted);
        }

        if ($level == 3 && $parent) {
            $items = MasterKlasifikasi::where('kode_klasifikasi', 'like', $parent . '.%')
                        ->select('id', 'kode_klasifikasi', 'jenis_arsip', 'masa_simpan', 'tindakan_akhir', 'hak_akses')
                        ->get();

            $formatted = $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'code' => $item->kode_klasifikasi,
                    'label' => $item->kode_klasifikasi . ' - ' . $item->jenis_arsip,
                    'masa_simpan' => $item->masa_simpan,
                    'tindakan_akhir' => $item->tindakan_akhir,
                    'hak_akses' => $item->hak_akses
                ];
            });
            return response()->json($formatted);
        }
        return response()->json([]);
    }

    private function getKategoriLabel($code)
    {
        $labels = [
            'HK' => 'HK - HUKUM', 'HM' => 'HM - HUMAS', 'KK' => 'KK - K3 & LINGKUNGAN',
            'KM' => 'KM - KEAMANAN', 'KS' => 'KS - KESEKRETARIATAN', 'KU' => 'KU - KEUANGAN',
            'PB' => 'PB - PERBEKALAN', 'PW' => 'PW - PENGAWASAN', 'SM' => 'SM - SDM',
            'BJ' => 'BJ - KEBIJAKAN', 'DT' => 'DT - DISTRIBUSI', 'LB' => 'LB - LITBANG',
            'MR' => 'MR - MANAJEMEN RISIKO', 'PR' => 'PR - PRODUKSI', 'PS' => 'PS - PEMASARAN', 'PM' => 'PM - PEMELIHARAAN',
        ];
        return $labels[$code] ?? $code;
    }

    private function getSubKategoriLabel($code)
    {
        $labels = [
            'HK.00' => 'HK.00 - Peraturan', 'HK.01' => 'HK.01 - Tanah/Bangunan', 'HK.02' => 'HK.02 - Surat Berharga', 'HK.03' => 'HK.03 - Dokumen Legal',
            'HM.00' => 'HM.00 - Penerangan', 'HM.01' => 'HM.01 - Protokoler', 'HM.02' => 'HM.02 - Publikasi', 'HM.03' => 'HM.03 - Rekanan', 'HM.04' => 'HM.04 - Bantuan Lingkungan', 'HM.05' => 'HM.05 - Kemitraan', 'HM.06' => 'HM.06 - Sarana Prasarana',
            'KK.00' => 'KK.00 - Identifikasi', 'KK.01' => 'KK.01 - Penilaian', 'KK.02' => 'KK.02 - Pemantauan',
            'KM.00' => 'KM.00 - Keamanan Intern', 'KM.01' => 'KM.01 - Keamanan Ekstern', 'KM.02' => 'KM.02 - Kerjasama Aparat', 'KM.03' => 'KM.03 - Koordinasi Pemerintah',
            'KS.00' => 'KS.00 - Surat Menyurat', 'KS.01' => 'KS.01 - Laporan', 'KS.02' => 'KS.02 - Kearsipan', 'KS.03' => 'KS.03 - Supplies Kantor',
            'KU.00' => 'KU.00 - Anggaran', 'KU.01' => 'KU.01 - Perbendaharaan', 'KU.02' => 'KU.02 - Akuntansi',
            'PB.00' => 'PB.00 - Kinerja Suplier', 'PB.01' => 'PB.01 - Pengadaan Barang', 'PB.02' => 'PB.02 - Pengadaan Jasa', 'PB.03' => 'PB.03 - Penerimaan Barang', 'PB.04' => 'PB.04 - Pengeluaran Barang',
            'PW.00' => 'PW.00 - Pemeriksaan Ekstern', 'PW.01' => 'PW.01 - Pengawasan Intern', 'PW.02' => 'PW.02 - Pengawasan Anak Perusahaan', 'PW.03' => 'PW.03 - Pengawasan Intercompany',
            'SM.00' => 'SM.00 - Formasi', 'SM.01' => 'SM.01 - Penerimaan Karyawan', 'SM.02' => 'SM.02 - Penilaian', 'SM.03' => 'SM.03 - Penggajian', 'SM.04' => 'SM.04 - Kesejahteraan', 'SM.05' => 'SM.05 - Diklat', 'SM.06' => 'SM.06 - PHK', 'SM.07' => 'SM.07 - Administrasi', 'SM.08' => 'SM.08 - Evaluasi Organisasi', 'SM.09' => 'SM.09 - Kesepakatan', 'SM.10' => 'SM.10 - Personal File',
            'BJ.00' => 'BJ.00 - Penetapan Kebijakan', 'DT.00' => 'DT.00 - Transportir', 'DT.01' => 'DT.01 - Distribusi Laut', 'DT.02' => 'DT.02 - Distribusi Umum', 'LB.00' => 'LB.00 - Penelitian', 'LB.01' => 'LB.01 - Rancang Bangun', 'LB.02' => 'LB.02 - Pengembangan', 'MR.00' => 'MR.00 - Manajemen GCG', 'MR.01' => 'MR.01 - Manajemen Risiko', 'MR.02' => 'MR.02 - Kajian Risiko',
            'PR.00' => 'PR.00 - Rencana Prod. Bahan Baku', 'PR.01' => 'PR.01 - Realisasi Bahan Baku', 'PR.02' => 'PR.02 - Mutu Bahan baku', 'PR.03' => 'PR.03 - Evaluasi Alat Baku', 'PR.04' => 'PR.04 - Rencana Prod. Klinker', 'PR.05' => 'PR.05 - Realisasi Klinker', 'PR.06' => 'PR.06 - Mutu Klinker', 'PR.07' => 'PR.07 - Evaluasi Alat Klinker', 'PR.08' => 'PR.08 - Rencana Prod. Semen', 'PR.09' => 'PR.09 - Realisasi Semen', 'PR.10' => 'PR.10 - Mutu Semen', 'PR.11' => 'PR.11 - Evaluasi Alat Semen',
            'PS.00' => 'PS.00 - Rencana pemasaran', 'PS.01' => 'PS.01 - Distributor', 'PS.02' => 'PS.02 - Kebutuhan Pasar', 'PS.03' => 'PS.03 - Evaluasi Pemasaran', 'PS.04' => 'PS.04 - Penjualan', 'PS.05' => 'PS.05 - Promosi',
            'PM.00' => 'PM.00 - Peralatan Produksi', 'PM.01' => 'PM.01 - Peralatan Penunjang', 'PM.03' => 'PM.03 - Peralatan Uji', 'PM.04' => 'PM.04 - Energi Listrik', 'PM.05' => 'PM.05 - Sistem Informasi',
        ];
        return $labels[$code] ?? $code;
    }
}
