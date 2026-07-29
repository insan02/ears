<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\ArsipMusnah; // New Model
use App\Models\MasterKlasifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Transaction
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArsipImport;

class ArsipController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ArsipImport, $request->file('file'));
            return back()->with('success', 'Data arsip berhasil diimport!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Import Exception: " . $e->getMessage());
            return back()->withErrors(['file' => 'Gagal import: ' . $e->getMessage()]);
        }
    }

    public function showImportForm()
    {
        return view('arsip.import');
    }
    public function index(Request $request)
    {
        $query = Arsip::with(['klasifikasi']); // Removed isiArsip relation

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
                  ->orWhere('tindakan_akhir', 'like', "%{$search}%")
                  // Search Relations
                  ->orWhereHas('klasifikasi', function($q3) use ($search) {
                      $q3->where('kode_klasifikasi', 'like', "%{$search}%")
                         ->orWhere('jenis_arsip', 'like', "%{$search}%");
                  })
                  // Search Formatted Date (Matches display format e.g. "15 Jun 1993")
                  ->orWhereRaw("DATE_FORMAT(tanggal_masuk, '%d %b %Y') LIKE ?", ["%{$search}%"])
                  // Search Original Date format just in case
                  ->orWhere('tanggal_masuk', 'like', "%{$search}%");
            });
        }

        // Filter by Tindakan Akhir (Permanen / Musnah)
        if ($request->has('filter_tindakan') && $request->filter_tindakan != '') {
            $query->where('tindakan_akhir', $request->filter_tindakan);
        }

        // Filter by Tahun (Specific Year)
        if ($request->has('filter_tahun') && $request->filter_tahun != '') {
            $query->where('tahun', $request->filter_tahun);
        }

        // Filter by Box
        if ($request->has('filter_box') && $request->filter_box != '') {
             $query->where('no_box', $request->filter_box);
        }

        // Sorting logic
        switch ($request->input('sort')) {
            case 'oldest':
                $query->orderBy('id', 'asc');
                break;
            case 'year_desc':
                $query->orderBy('tahun', 'desc');
                break;
            case 'year_asc':
                $query->orderBy('tahun', 'asc');
                break;
            case 'box_desc':
                $query->orderBy('no_box', 'desc');
                break;
            case 'box_asc':
                $query->orderBy('no_box', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        // Check for Print Mode
        $printMode = $request->get('print') === 'true';

        if ($printMode) {
             $arsips = $query->get();
        } else {
             $arsips = $query->paginate(100);
        }

        // Calculate grouping and numbering based on Entry Order (First ID)
        $groupData = [];
        $lastNoBerkasOnPage = null;

        // Fetch all unique no_berkas ordered by the time they were first created (min id)
        // This ensures Number 1 is the first file ever inserted, regardless of Year.
        $allGroups = Arsip::selectRaw('no_berkas, MIN(id) as first_id')
                        ->groupBy('no_berkas')
                        ->orderBy('first_id', 'asc')
                        ->get();

        // Create a fast lookup map: no_berkas => Rank (1 based)
        $rankMap = [];
        foreach ($allGroups as $index => $g) {
            $rankMap[$g->no_berkas] = $index + 1;
        }

        foreach ($arsips as $arsip) {
            $currentNo = $arsip->no_berkas;
            $number = $rankMap[$currentNo] ?? 0;

            // Determine visibility (Start of group on this page)
            $isStart = ($currentNo !== $lastNoBerkasOnPage);

            $groupData[$arsip->id] = [
                'number' => $number,
                'is_start' => $isStart
            ];

            $lastNoBerkasOnPage = $currentNo;
        }

        // Get available years for filter
        $availableYears = Arsip::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // Get available boxes for filter
        // Sort effectively might need raw query if box is numeric string, but simple orderBy is usually okay
        // unless mix of numbers and letters heavily implies numeric sort needed.
        // Assuming simple string sort or numeric sort.
        $availableBoxes = Arsip::select('no_box')
                            ->whereNotNull('no_box')
                            ->where('no_box', '!=', '-')
                            ->distinct()
                            ->orderByRaw('CAST(no_box AS UNSIGNED) ASC') // Try numeric sort first
                            ->pluck('no_box');

        if ($request->ajax()) {
            return view('arsip.partials.table', compact('arsips', 'groupData'));
        }

        return view('arsip.arsip', compact('arsips', 'availableYears', 'availableBoxes', 'groupData', 'printMode'));
    }

    public function create()
    {
        $klasifikasis = MasterKlasifikasi::all();
        $units = \App\Models\Unit::all(); // Fetch all units
        // Calculate next number (Global Rank)
        $nextNumber = Arsip::distinct('no_berkas')->count() + 1;

        return view('arsip.input-arsip', compact('klasifikasis', 'nextNumber', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_berkas' => 'required|string',
            // 'unit_pengolah' => 'required|string', // Moved to per-item
            // 'klasifikasi_id' => 'required|exists:master_klasifikasi,id', // Moved to per-item
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

        // Auto Generate No Berkas (Simple Sequential Number)
        // Count distinct existing groups to determine next number
        $currentCount = Arsip::distinct('no_berkas')->count();
        $nextNo = $currentCount + 1;
        $no_berkas = (string) $nextNo;

        // Ensure user exists
        $user = \App\Models\User::first();
        if (!$user) {
            $user = \App\Models\User::create([
                'nama' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Loop and Create Individual Records
        // This effectively "flattens" the structure: 1 Item = 1 Row in Arsip Table
        foreach ($validated['isi_berkas'] as $item) {
            Arsip::create([
                // Shared Identity
                'no_berkas'     => $no_berkas,
                'klasifikasi_id'=> $item['klasifikasi_id'],
                'nama_berkas'   => $validated['nama_berkas'],
                'unit_pengolah' => $item['unit_pengolah'],
                'user_id'       => $user->id,

                // Item Specifics (Now directly in Arsip table)
                'isi'           => $item['isi'],
                'tahun'         => $item['tahun'] ?? null,
                'tanggal_masuk' => $item['tanggal'],
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

        // Use existing nextNumber logic (though we won't use it for creation, just to keep view happy or we pass actual)
        // Actually, we should pass the existing no_berkas
        $nextNumber = $arsip->no_berkas;

        // Format data for the view's JS (similar to isi_berkas structure)
        // We only have ONE item here because we are editing a single row ID from the index
        $initialData = [
            [
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
            ]
        ];

        $units = \App\Models\Unit::all(); // Fetch all units

        return view('arsip.input-arsip', compact('arsip', 'nextNumber', 'initialData', 'units'));
    }

    public function destroy($id)
    {
        $arsip = Arsip::find($id);
        if (!$arsip) return redirect()->back()->with('error', 'Data tidak ditemukan');

        if ($arsip->tindakan_akhir == 'Musnah') {
            try {
                DB::transaction(function () use ($arsip) {
                    $data = $arsip->toArray();

                    // Remove ID to allow new auto-increment in trash table
                    unset($data['id']);

                    // Add timestamp
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
        // 1. EAGER LOADING: Memanggil relasi 'klasifikasi' sejak awal
        // Ini wajib karena di view kita menggunakan $arsip->klasifikasi->kode_klasifikasi
        // Jika tidak pakai 'with', Laravel akan melakukan query database berulang kali (N+1 Problem)
        $query = ArsipMusnah::with('klasifikasi');

        if ($request->filled('search')) {
            $search = $request->search;

            // 2. CLOSURE PENCARIAN: Dibungkus dalam function()
            // Agar kondisi 'orWhere' tidak bentrok jika nanti Anda menambahkan filter lain
            $query->where(function($q) use ($search) {
                $q->where('nama_berkas', 'like', "%{$search}%")
                  ->orWhere('no_berkas', 'like', "%{$search}%")
                  ->orWhere('isi', 'like', "%{$search}%"); // Tambahan: cari dari Uraian juga
            });
        }

        // 3. WITH QUERY STRING: Sangat penting untuk Pagination
        $arsips = $query->orderBy('deleted_at', 'desc')->paginate(25)->withQueryString();

        return view('arsip.musnah', compact('arsips'));
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

        // Since we are editing a SPECIFIC Single Row ID, we only take the FIRST item from the list
        // (The UI enforces 1 item in edit mode usually, or we just take the first one if they added multiple - but logically edit is for one item)
        // However, if the user "Adds" more items in Edit Mode, it's ambiguous.
        // For SAFETY in this specific "Edit Row" context, we update the CURRENT ID with the FIRST item data.
        // If they added *new* items in the list, we optionally could create them, but that complicates "Edit Single Item".
        // Let's assume strict 1-to-1 update for simplicity as per "Edit Data" on a row context.

        $item = $validated['isi_berkas'][0];

        $arsip->update([
            'nama_berkas'   => $validated['nama_berkas'],
            'klasifikasi_id'=> $item['klasifikasi_id'],
            'unit_pengolah' => $item['unit_pengolah'],
            'isi'           => $item['isi'],
            'tahun'         => $item['tahun'] ?? null,
            'tanggal_masuk' => $item['tanggal'],
            'jumlah'        => $item['jumlah'] ?? 1,
            'no_box'        => $item['no_box'] ?? '-',
            'hak_akses'     => $item['hak_akses'] ?? '-',
            'jenis_media'   => $item['jenis_media'] ?? 'Kertas',
            'masa_simpan'   => $item['masa_simpan'] ?? '-',
            'tindakan_akhir'=> $item['tindakan_akhir'] ?? '-',
        ]);

        return redirect('/arsip')->with('success', 'Data arsip berhasil diperbarui!');
    }

    public function export(Request $request)
    {
        $type = $request->input('type');
        $ids = json_decode($request->input('ids'), true);
        $search = $request->input('search');
        $sort = $request->input('sort');
        $filter_tindakan = $request->input('filter_tindakan');
        $filter_tahun = $request->input('filter_tahun');

        // Prepare query for PDF (Excel uses Export class)
        // Ideally, we reuse logic. Let's extract query logic or use the Export class query.
        $export = new \App\Exports\ArsipExport($ids, $search, $sort, $filter_tindakan, $filter_tahun);
        $filename = 'arsip-all-' . date('Y-m-d') . ($type === 'pdf' ? '.pdf' : '.xlsx');

        if ($type === 'excel') {
            return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
        }



        return redirect()->back();
    }
    public function getKlasifikasiOptions(Request $request)
    {
        $level = $request->input('level', 0); // Default to 0 now
        $parent = $request->input('parent');
        $jraType = $request->input('jra_type'); // New input

        // Level 0: Choose JRA Type
        if ($level == 0) {
            return response()->json([
                ['code' => 'Fasilitatif', 'label' => 'JRA Fasilitatif'],
                ['code' => 'Substantif', 'label' => 'JRA Substantif'],
            ]);
        }

        // Level 1: Pokok Masalah (filtered by JRA Type)
        if ($level == 1) {
            // Note: We removed 'jenis_jra' column from master_klasifikasi in revision,
            // so filtering by 'jenis_jra' in DB might fail or needs adjustment if we rely on it.
            // But wait, the user said "gabung aja", so maybe we don't filter in DB?
            // "3. pilihan substantif dan fasilitatif digabung aja, jadi di database kolom jenis jra nya dihapus aja"
            // So we should NOT filter by jraType anymore in logic?
            // But the UI still starts with "Pilih Jenis JRA"? NO, user said "digabung aja".
            // If DB column is gone, I cannot filter.
            // I should assume the UI flow might change or simply show ALL at level 1?
            // The user revisions said "digabung aja".
            // So Level 0 (JRA Type selection) might be REDUNDANT now?
            // "3. pilihan substantif dan fasilitatif digabung aja"
            // Wait, if I keep the UI Step 0, it does nothing if I return same data?
            // Actually, I should probably Remove Step 0 in Frontend logic too if they are merged.
            // But for now, let's just make it return all codes regardless of type input.

            $query = MasterKlasifikasi::select('kode_klasifikasi');

            // if ($jraType) { $query->where('jenis_jra', $jraType); } // Column removed

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
                    'hak_akses' => $item->hak_akses // Include hak_akses
                ];
            });

            return response()->json($formatted);
        }

        return response()->json([]);
    }

    private function getKategoriLabel($code)
    {
        $labels = [
            'HK' => 'HK - HUKUM',
            'HM' => 'HM - HUMAS',
            'KK' => 'KK - KESELAMATAN, KESEHATAN KERJA & LINGKUNGAN HIDUP',
            'KM' => 'KM - KEAMANAN',
            'KS' => 'KS - KESEKRETARIATAN',
            'KU' => 'KU - KEUANGAN',
            'PB' => 'PB - PERBEKALAN',
            'PW' => 'PW - PENGAWASAN & SISTEM MANAJEMEN',
            'SM' => 'SM - SUMBER DAYA MANUSIA',

            // JRA Substantif
            'BJ' => 'BJ - KEBIJAKAN',
            'DT' => 'DT - DISTRIBUSI DAN TRANSPORTASI',
            'LB' => 'LB - PENELITIAN & PENGEMBANGAN',
            'MR' => 'MR - MANAJEMEN GCG & RISIKO',
            'PR' => 'PR - PRODUKSI',
            'PS' => 'PS - PEMASARAN',
            'PM' => 'PM - PEMELIHARAAN',
        ];
        return $labels[$code] ?? $code;
    }

    private function getSubKategoriLabel($code)
    {
        $labels = [
            // HK
            'HK.00' => 'HK.00 - Peraturan',
            'HK.01' => 'HK.01 - Tanah / Bangunan',
            'HK.02' => 'HK.02 - Surat Berharga',
            'HK.03' => 'HK.03 - Dokumen Legal',

            // HM
            'HM.00' => 'HM.00 - Penerangan',
            'HM.01' => 'HM.01 - Protokoler',
            'HM.02' => 'HM.02 - Publikasi',
            'HM.03' => 'HM.03 - Rekanan',
            'HM.04' => 'HM.04 - Bantuan Bina Lingkungan',
            'HM.05' => 'HM.05 - Kemitraan',
            'HM.06' => 'HM.06 - Sarana dan Prasarana',

            // KK
            'KK.00' => 'KK.00 - Identifikasi',
            'KK.01' => 'KK.01 - Penilaian',
            'KK.02' => 'KK.02 - Pemantauan dan Pengendalian',

            // KM
            'KM.00' => 'KM.00 - Keamanan Lingkungan Intern',
            'KM.01' => 'KM.01 - Keamanan Lingkungan Ekstern',
            'KM.02' => 'KM.02 - Kerjasama dengan Aparat Hukum',
            'KM.03' => 'KM.03 - Koordinasi Keamanan Pemerintah',

            // KS
            'KS.00' => 'KS.00 - Surat Menyurat',
            'KS.01' => 'KS.01 - Laporan',
            'KS.02' => 'KS.02 - Kearsipan',
            'KS.03' => 'KS.03 - Supplies Kantor',

            // KU
            'KU.00' => 'KU.00 - Anggaran',
            'KU.01' => 'KU.01 - Perbendaharaan',
            'KU.02' => 'KU.02 - Akuntansi',

            // PB
            'PB.00' => 'PB.00 - Kinerja Suplier',
            'PB.01' => 'PB.01 - Pengadaan Barang',
            'PB.02' => 'PB.02 - Pengadaan Jasa',
            'PB.03' => 'PB.03 - Penerimaan Barang',
            'PB.04' => 'PB.04 - Pengeluaran Barang',

            // PW
            'PW.00' => 'PW.00 - Pemeriksaan Ekstern',
            'PW.01' => 'PW.01 - Pengawasan Intern',
            'PW.02' => 'PW.02 - Pengawasan Anak Perusahaan & Lembaga Penunjang',
            'PW.03' => 'PW.03 - Pengawasan Intercompany SMIG',

            // SM
            'SM.00' => 'SM.00 - Formasi',
            'SM.01' => 'SM.01 - Penerimaan Karyawan',
            'SM.02' => 'SM.02 - Penilaian',
            'SM.03' => 'SM.03 - Penggajian',
            'SM.04' => 'SM.04 - Kesejahteraan',
            'SM.05' => 'SM.05 - Pendidikan dan Pelatihan',
            'SM.06' => 'SM.06 - Pemberhentian Hubungan Kerja',
            'SM.07' => 'SM.07 - Administrasi Karyawan',
            'SM.08' => 'SM.08 - Evaluasi Organisasi',
            'SM.09' => 'SM.09 - Komitmen / Kesepakatan',
            'SM.10' => 'SM.10 - Personal File',
            'SM.10' => 'SM.10 - Personal File',

            // Substantif placeholders (to be expanded fully if needed, but for now fallback logic is fine or basic ones)
            'BJ.00' => 'BJ.00 - Penetapan Kebijakan',
            'DT.00' => 'DT.00 - Transportir',
            'DT.01' => 'DT.01 - Distribusi & Transportasi Laut',
            'DT.02' => 'DT.02 - Distribusi dan Transportasi',
            'LB.00' => 'LB.00 - Penelitian',
            'LB.01' => 'LB.01 - Rancang Bangun',
            'LB.02' => 'LB.02 - Pengembangan',
            'MR.00' => 'MR.00 - Manajemen GCG',
            'MR.01' => 'MR.01 - Manajemen Risiko',
            'MR.02' => 'MR.02 - Kajian Risiko',
            'MR.03' => 'MR.03',
            'PR.00' => 'PR.00 - Rencana Produksi Bahan Baku',
            'PR.01' => 'PR.01 - Realisasi Produksi Bahan Baku',
            'PR.02' => 'PR.02 - Mutu Produk & Bahan baku',
            'PR.03' => 'PR.03 - Evaluasi Kinerja Peralatan Produksi dan Mutu Bahan Baku',
            'PR.04' => 'PR.04 - Rencana Produksi Terak/Klinker',
            'PR.05' => 'PR.05 - Realisasi Produksi Terak/ Klinker',
            'PR.06' => 'PR.06 - Mutu Produk & Bahan Terak / klinker',
            'PR.07' => 'PR.07 - Evaluasi Kinerja Peralatan Produksi dan Mutu Produk Terak / Klinker',
            'PR.08' => 'PR.08 - Rencana Produksi Semen',
            'PR.09' => 'PR.09 - Realisasi Produksi Semen',
            'PR.10' => 'PR.10 - Mutu Produk & Bahan Produksi Semen',
            'PR.11' => 'PR.11 - Evaluasi Kinerja Peralatan Produksi dan Mutu Produk Semen',
            'PS.00' => 'PS.00 - Rencana pemasaran',
            'PS.01' => 'PS.01 - Distributor',
            'PS.02' => 'PS.02 - Kebutuhan Pasar',
            'PS.03' => 'PS.03 - Evaluasi Pemasaran',
            'PS.04' => 'PS.04 - Penjualan',
            'PS.05' => 'PS.05 - Promosi',
            'PM.00' => 'PM.00 - Peralatan Produksi',
            'PM.01' => 'PM.01 - Peralatan Penunjang Produksi',
            'PM.03' => 'PM.03 - Peralatan Uji Mutu',
            'PM.04' => 'PM.04 - Energi Listrik',
            'PM.05' => 'PM.05 - Sistem Informasi',

        ];
        return $labels[$code] ?? $code;
    }
}
