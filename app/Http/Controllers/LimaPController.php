<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LimaPContent;
use App\Models\LimaPKaizen;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class LimaPController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                $allowedForUsers = ['index', 'show'];
                if (!in_array($request->route()->getActionMethod(), $allowedForUsers)) {
                    if (Auth::user()->role !== 'admin') {
                        abort(403, 'Akses Ditolak. Fitur khusus Administrator.');
                    }
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $data = LimaPContent::orderBy('id', 'desc')->get();
        return view('limap.index', compact('data'));
    }

    public function create()
    {
        return view('limap.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pic' => 'required|string|max:50',
            'kesepakatan' => 'nullable|array|max:3',
            'kesepakatan.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'visi_misi' => 'nullable|array|max:3',
            'visi_misi.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'pembagian_area' => 'nullable|array|max:3',
            'pembagian_area.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'struktur' => 'nullable|array|max:3',
            'struktur.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'jadwal_kegiatan' => 'nullable|array|max:3',
            'jadwal_kegiatan.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'pic.required' => 'Nama PIC Area wajib diisi.',
            'pic.max' => 'Nama PIC Area maksimal 50 karakter.',
            'kesepakatan.max' => 'Gambar Kesepakatan maksimal 3 foto.',
            'visi_misi.max' => 'Gambar Visi & Misi maksimal 3 foto.',
            'pembagian_area.max' => 'Gambar Pembagian Area maksimal 3 foto.',
            'struktur.max' => 'Gambar Struktur Organisasi maksimal 3 foto.',
            'jadwal_kegiatan.max' => 'Gambar Jadwal Kegiatan maksimal 3 foto.',
            '*.image' => 'File yang diunggah harus berupa gambar.',
            '*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            '*.max' => 'Ukuran setiap gambar maksimal 2 MB (2048 KB).'
        ]);

        $data = ['pic' => $request->pic];
        $fields = ['kesepakatan', 'visi_misi', 'pembagian_area', 'struktur', 'jadwal_kegiatan'];

        foreach ($fields as $field) {
            $paths = [];
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $paths[] = $file->store("limap/{$field}", 'public');
                }
            }
            $data[$field] = $paths;
        }

        LimaPContent::create($data);
        return redirect()->route('limap.index')->with('success', 'Data 5P berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = LimaPContent::findOrFail($id);
        return view('limap.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $item = LimaPContent::findOrFail($id);

        $request->validate([
            'pic' => 'required|string|max:50',
            'kesepakatan.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'visi_misi.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'pembagian_area.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'struktur.*' => 'image|mimes:jpeg,png,jpg|max:2048',
            'jadwal_kegiatan.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'pic.required' => 'Nama PIC Area wajib diisi.',
            'pic.max' => 'Nama PIC Area maksimal 50 karakter.',
            '*.image' => 'File yang diunggah harus berupa gambar.',
            '*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            '*.max' => 'Ukuran setiap gambar maksimal 2 MB (2048 KB).'
        ]);

        $fields = ['kesepakatan', 'visi_misi', 'pembagian_area', 'struktur', 'jadwal_kegiatan'];
        $fieldLabels = [
            'kesepakatan' => 'Kesepakatan',
            'visi_misi' => 'Visi & Misi',
            'pembagian_area' => 'Pembagian Area',
            'struktur' => 'Struktur Organisasi',
            'jadwal_kegiatan' => 'Jadwal Kegiatan'
        ];

        // Validasi total gambar (gambar lama yang dipertahankan + gambar baru diunggah) maksimal 3
        foreach ($fields as $field) {
            $existing = $item->$field ?? [];
            $deleted = $request->input("hapus_{$field}", []);
            $remainingOldCount = count(array_diff($existing, $deleted));
            $newFilesCount = $request->hasFile($field) ? count($request->file($field)) : 0;

            if ($remainingOldCount + $newFilesCount > 3) {
                return back()->withErrors([
                    $field => "Total gambar {$fieldLabels[$field]} (lama + baru) tidak boleh lebih dari 3 foto."
                ])->withInput();
            }
        }

        $dataToUpdate = ['pic' => $request->pic];

        foreach ($fields as $field) {
            $existingImages = $item->$field ?? [];

            // 1. Hapus gambar yang ditandai hapus
            if ($request->has("hapus_{$field}")) {
                foreach ($request->input("hapus_{$field}") as $path) {
                    Storage::disk('public')->delete($path);
                    $existingImages = array_diff($existingImages, [$path]);
                }
            }

            // 2. Tambahkan gambar baru
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $existingImages[] = $file->store("limap/{$field}", 'public');
                }
            }

            $dataToUpdate[$field] = array_values($existingImages);
        }

        $item->update($dataToUpdate);
        return redirect()->route('limap.show', $id)->with('success', 'Data 5P berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = LimaPContent::findOrFail($id);
        $fields = ['kesepakatan', 'visi_misi', 'pembagian_area', 'struktur', 'jadwal_kegiatan'];

        foreach ($fields as $field) {
            if (is_array($item->$field)) {
                foreach ($item->$field as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        foreach ($item->kaizens as $kaizen) {
            Storage::disk('public')->delete($kaizen->file_path);
        }

        $item->delete();
        return redirect()->route('limap.index')->with('success', 'Data 5P beserta file berhasil dihapus!');
    }

    public function storeKaizen(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'kaizen_files' => 'required|array',
            'kaizen_files.*' => 'required|file|mimes:pdf|max:10240'
        ], [
            'tahun.required' => 'Tahun wajib diisi.',
            'bulan.required' => 'Bulan wajib dipilih.',
            'kaizen_files.required' => 'Pilih minimal satu file Kaizen.',
            'kaizen_files.*.mimes' => 'Format file harus berupa dokumen PDF.',
            'kaizen_files.*.max' => 'Ukuran file PDF maksimal 10 MB (10240 KB) per file.'
        ]);

        if ($request->hasFile('kaizen_files')) {
            foreach ($request->file('kaizen_files') as $file) {
                LimaPKaizen::create([
                    'lima_p_content_id' => $id,
                    'tahun' => $request->tahun,
                    'bulan' => $request->bulan,
                    'file_path' => $file->store('limap/kaizen_pdf', 'public'),
                    'original_name' => $file->getClientOriginalName()
                ]);
            }
        }
        return redirect()->back()->with('success', 'File Kaizen berhasil diunggah!');
    }

    public function destroyKaizen($id)
    {
        $kaizen = LimaPKaizen::findOrFail($id);
        Storage::disk('public')->delete($kaizen->file_path);
        $kaizen->delete();
        return redirect()->back()->with('success', 'File Kaizen berhasil dihapus!');
    }

    public function show($id)
    {
        $data = LimaPContent::with('kaizens')->findOrFail($id);
        return view('limap.show', compact('data'));
    }
}
