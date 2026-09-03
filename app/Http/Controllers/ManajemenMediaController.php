<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaInformasi;
use Illuminate\Support\Facades\Storage;

class ManajemenMediaController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaInformasi::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
        }

        $media = $query->latest('tanggal')->paginate(15)->withQueryString();
        return view('manajemen-media.index', compact('media'));
    }

    public function create()
    {
        return view('manajemen-media.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string|max:100',
            'gambar' => 'required|array|max:5',
            'gambar.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul maksimal berisi 50 karakter.',
            'deskripsi.required' => 'Deskripsi berita wajib diisi.',
            'deskripsi.max' => 'Deskripsi maksimal berisi 100 karakter.',
            'gambar.required' => 'Minimal harus mengupload 1 gambar.',
            'gambar.max' => 'Maksimal upload adalah 5 gambar.',
            'gambar.*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'gambar.*.max' => 'Ukuran gambar maksimal 2 MB.'
        ]);

        $imagePaths = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                // Menyimpan langsung ke folder storage/app/public/media
                $path = $file->store('media', 'public');
                $imagePaths[] = $path;
            }
        }

        $validated['gambar'] = json_encode($imagePaths);
        MediaInformasi::create($validated);

        return redirect()->route('manajemen-media.index')->with('success', 'Berita berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $media = MediaInformasi::findOrFail($id);
        return view('manajemen-media.edit', compact('media'));
    }

    public function update(Request $request, string $id)
    {
        $media = MediaInformasi::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string|max:100',
            'keep_gambar' => 'nullable|array',
            'gambar' => 'nullable|array',
            'gambar.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul maksimal berisi 50 karakter.',
            'deskripsi.required' => 'Deskripsi berita wajib diisi.',
            'deskripsi.max' => 'Deskripsi maksimal berisi 100 karakter.',
            'gambar.*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'gambar.*.max' => 'Ukuran gambar maksimal 2 MB.'
        ]);

        $keepImages = $request->keep_gambar ?? [];
        $newFilesCount = $request->hasFile('gambar') ? count($request->file('gambar')) : 0;

        if (count($keepImages) + $newFilesCount > 5) {
            return back()->withErrors(['gambar' => 'Total gambar (lama + baru) tidak boleh lebih dari 5.']);
        }
        if (count($keepImages) + $newFilesCount == 0) {
            return back()->withErrors(['gambar' => 'Minimal harus ada 1 gambar.']);
        }

        // Hapus gambar lama dari Storage
        $oldImages = json_decode($media->gambar, true);
        if (!is_array($oldImages)) $oldImages = [$media->gambar];

        foreach ($oldImages as $oldPath) {
            if (!in_array($oldPath, $keepImages)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $finalImages = $keepImages;
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                // Menyimpan langsung ke folder storage/app/public/media
                $path = $file->store('media', 'public');
                $finalImages[] = $path;
            }
        }

        $media->update([
            'judul' => $request->judul,
            'tanggal' => $request->tanggal,
            'deskripsi' => $request->deskripsi,
            'gambar' => json_encode(array_values($finalImages)),
        ]);

        return redirect()->route('manajemen-media.index')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $media = MediaInformasi::findOrFail($id);

        $oldImages = json_decode($media->gambar, true);
        if (!is_array($oldImages)) $oldImages = [$media->gambar];

        // Hapus semua gambar terkait dari Storage
        foreach ($oldImages as $oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        $media->delete();
        return redirect()->route('manajemen-media.index')->with('success', 'Berita berhasil dihapus');
    }
}
