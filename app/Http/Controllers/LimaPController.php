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
                // Yang boleh diakses user biasa hanya index dan show
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
            'pic' => 'required|string|max:255',
            'kesepakatan.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'visi_misi.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'pembagian_area.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'struktur.*' => 'image|mimes:jpeg,png,jpg|max:5120',
            'jadwal_kegiatan.*' => 'image|mimes:jpeg,png,jpg|max:5120',
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
            $data[$field] = $paths; // Laravel akan otomatis merubahnya ke JSON karena Model Casting
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
        $fields = ['kesepakatan', 'visi_misi', 'pembagian_area', 'struktur', 'jadwal_kegiatan'];

        $dataToUpdate = ['pic' => $request->pic];

        foreach ($fields as $field) {
            $existingImages = $item->$field ?? [];

            // 1. Hapus gambar yang dicentang user
            if ($request->has("hapus_{$field}")) {
                foreach ($request->input("hapus_{$field}") as $path) {
                    Storage::disk('public')->delete($path);
                    $existingImages = array_diff($existingImages, [$path]);
                }
            }

            // 2. Tambahkan gambar baru (append)
            if ($request->hasFile($field)) {
                foreach ($request->file($field) as $file) {
                    $existingImages[] = $file->store("limap/{$field}", 'public');
                }
            }

            // Re-index array agar JSON rapi
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

        // PDF Kaizen otomatis terhapus dari DB karena cascadeOnDelete,
        // tapi file fisiknya harus dihapus manual
        foreach ($item->kaizens as $kaizen) {
            Storage::disk('public')->delete($kaizen->file_path);
        }

        $item->delete();
        return redirect()->route('limap.index')->with('success', 'Data 5P beserta file berhasil dihapus!');
    }

    // --- KAIZEN (PDF) MANAGEMENT ---
    public function storeKaizen(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|min:1|max:12',
            'kaizen_files.*' => 'required|mimes:pdf|max:10240' // Maks 10MB per file
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
        // Load data berserta kaizens-nya
        $data = LimaPContent::with('kaizens')->findOrFail($id);
        return view('limap.show', compact('data'));
    }
}
