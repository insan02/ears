<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaInformasi;

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
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'gambar' => 'required|array|max:5',
            'gambar.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'gambar.required' => 'Minimal harus mengupload 1 gambar.',
            'gambar.max' => 'Maksimal upload adalah 5 gambar.',
            'gambar.*.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'gambar.*.max' => 'Ukuran gambar maksimal 5 MB.'
        ]);

        $imagePaths = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $safeName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $file->getClientOriginalName());
                $filename = time() . '_' . uniqid() . '_' . $safeName;
                $file->move(public_path('images/media'), $filename);
                $imagePaths[] = 'images/media/' . $filename;
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
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            'keep_gambar' => 'nullable|array', // Gambar lama yang dipertahankan
            'gambar' => 'nullable|array',      // Gambar baru yang ditambahkan
            'gambar.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Cek total gambar (Lama yg disimpan + Baru yg ditambah)
        $keepImages = $request->keep_gambar ?? [];
        $newFilesCount = $request->hasFile('gambar') ? count($request->file('gambar')) : 0;

        if (count($keepImages) + $newFilesCount > 5) {
            return back()->withErrors(['gambar' => 'Total gambar (lama + baru) tidak boleh lebih dari 5.']);
        }
        if (count($keepImages) + $newFilesCount == 0) {
            return back()->withErrors(['gambar' => 'Minimal harus ada 1 gambar.']);
        }

        // Hapus gambar lama dari server jika tidak ada di array 'keep_gambar'
        $oldImages = json_decode($media->gambar, true);
        if (!is_array($oldImages)) $oldImages = [$media->gambar];

        foreach ($oldImages as $oldPath) {
            if (!in_array($oldPath, $keepImages) && file_exists(public_path($oldPath))) {
                unlink(public_path($oldPath));
            }
        }

        // Proses gambar yang baru diupload
        $finalImages = $keepImages;
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $file) {
                $safeName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $file->getClientOriginalName());
                $filename = time() . '_' . uniqid() . '_' . $safeName;
                $file->move(public_path('images/media'), $filename);
                $finalImages[] = 'images/media/' . $filename;
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

        foreach ($oldImages as $oldPath) {
            if (file_exists(public_path($oldPath))) unlink(public_path($oldPath));
        }

        $media->delete();
        return redirect()->route('manajemen-media.index')->with('success', 'Berita berhasil dihapus');
    }
}
