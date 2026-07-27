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

        // Menambahkan fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('judul', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%");
        }

        // Pagination 10 per halaman
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
            // Validasi Gambar: Hanya JPG, JPEG, PNG, Maksimal 5 MB (5120 KB)
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'gambar.mimes' => 'Format gambar harus berupa JPG, JPEG, atau PNG.',
            'gambar.max' => 'Ukuran gambar tidak boleh lebih dari 5 MB.'
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            // Membersihkan nama file dari karakter aneh untuk keamanan
            $safeName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $file->getClientOriginalName());
            $filename = time() . '_' . $safeName;

            $file->move(public_path('images/media'), $filename);
            $validated['gambar'] = 'images/media/' . $filename;
        }

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

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'deskripsi' => 'required|string',
            // Validasi Gambar: Opsional, Hanya JPG, JPEG, PNG, Maksimal 5 MB
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'gambar.mimes' => 'Format gambar harus berupa JPG, JPEG, atau PNG.',
            'gambar.max' => 'Ukuran gambar tidak boleh lebih dari 5 MB.'
        ]);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($media->gambar && file_exists(public_path($media->gambar))) {
                unlink(public_path($media->gambar));
            }

            $file = $request->file('gambar');
            $safeName = preg_replace('/[^a-zA-Z0-9_.-]/', '', $file->getClientOriginalName());
            $filename = time() . '_' . $safeName;

            $file->move(public_path('images/media'), $filename);
            $validated['gambar'] = 'images/media/' . $filename;
        }

        $media->update($validated);

        return redirect()->route('manajemen-media.index')->with('success', 'Berita berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $media = MediaInformasi::findOrFail($id);

        // Hapus file gambar dari server
        if ($media->gambar && file_exists(public_path($media->gambar))) {
            unlink(public_path($media->gambar));
        }

        $media->delete();

        return redirect()->route('manajemen-media.index')->with('success', 'Berita berhasil dihapus');
    }
}
