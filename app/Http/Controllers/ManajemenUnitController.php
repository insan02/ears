<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ManajemenUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_unit', 'like', "%$search%")
                  ->orWhere('keterangan', 'like', "%$search%");
        }

        // Menggunakan Pagination (10 baris per halaman) agar lebih ringan
        $units = $query->latest()->paginate(30)->withQueryString();

        return view('manajemen-unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_unit' => 'required|string|max:50|unique:units,nama_unit', // Ubah max jadi 25
            'keterangan' => 'nullable|string|max:50' // Ubah max jadi 50
        ], [
            'nama_unit.unique' => 'Nama unit ini sudah terdaftar di sistem.',
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'nama_unit.max' => 'Nama unit maksimal 50 karakter.',
            'keterangan.max' => 'Keterangan maksimal 50 karakter.'
        ]);

        Unit::create($request->only(['nama_unit', 'keterangan']));

        return redirect()->route('manajemen-unit.index')->with('success', 'Unit berhasil ditambahkan!');
    }

    // Menambahkan string $id untuk Type Hinting
    public function update(Request $request, string $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'nama_unit' => [
                'required',
                'string',
                'max:50', // Ubah max jadi 25
                Rule::unique('units')->ignore($unit->id)
            ],
            'keterangan' => 'nullable|string|max:50' // Ubah max jadi 50
        ], [
            'nama_unit.unique' => 'Nama unit ini sudah terdaftar di sistem.',
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'nama_unit.max' => 'Nama unit maksimal 50 karakter.',
            'keterangan.max' => 'Keterangan maksimal 50 karakter.'
        ]);

        $unit->update($request->only(['nama_unit', 'keterangan']));

        return redirect()->route('manajemen-unit.index')->with('success', 'Data unit berhasil diperbarui!');
    }

    // Menambahkan string $id untuk Type Hinting
    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('manajemen-unit.index')->with('success', 'Unit berhasil dihapus!');
    }
}
