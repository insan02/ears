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

        $units = $query->latest()->paginate(30)->withQueryString();

        // CEK KETERHUBUNGAN: Menentukan apakah tombol hapus permanen boleh muncul
        foreach ($units as $unit) {
            $dipakaiDiArsipMasuk = \App\Models\ArsipMasuk::where('unit_asal', $unit->nama_unit)->exists();
            $dipakaiDiArsip      = \App\Models\Arsip::where('unit_pengolah', $unit->nama_unit)->exists();
            $dipakaiDiMonitoring = \App\Models\LogAktivitas::where('unit_kerja', $unit->nama_unit)->exists();

            // Jika tidak terhubung ke tabel manapun, status is_deletable = true
            $unit->is_deletable = !($dipakaiDiArsipMasuk || $dipakaiDiArsip || $dipakaiDiMonitoring);
        }

        return view('manajemen-unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_unit' => 'required|string|max:50|unique:units,nama_unit',
            'keterangan' => 'nullable|string|max:50'
        ], [
            'nama_unit.unique' => 'Nama unit ini sudah terdaftar di sistem.',
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'nama_unit.max' => 'Nama unit maksimal 50 karakter.',
            'keterangan.max' => 'Keterangan maksimal 50 karakter.'
        ]);

        Unit::create($request->only(['nama_unit', 'keterangan']));

        return redirect()->route('manajemen-unit.index')->with('success', 'Unit berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'nama_unit' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units')->ignore($unit->id)
            ],
            'keterangan' => 'nullable|string|max:50'
        ], [
            'nama_unit.unique' => 'Nama unit ini sudah terdaftar di sistem.',
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'nama_unit.max' => 'Nama unit maksimal 50 karakter.',
            'keterangan.max' => 'Keterangan maksimal 50 karakter.'
        ]);

        $unit->update($request->only(['nama_unit', 'keterangan']));

        return redirect()->route('manajemen-unit.index')->with('success', 'Data unit berhasil diperbarui!');
    }

    public function toggleStatus(string $id)
    {
        $unit = Unit::findOrFail($id);

        $unit->is_active = !$unit->is_active;
        $unit->save();

        $statusMessage = $unit->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('manajemen-unit.index')
                         ->with('success', "Unit kerja {$unit->nama_unit} berhasil {$statusMessage}!");
    }

    // FUNGSI HAPUS DIKEMBALIKAN
    public function destroy(string $id)
    {
        $unit = Unit::findOrFail($id);

        // Validasi ganda dari backend untuk keamanan
        $dipakaiDiArsipMasuk = \App\Models\ArsipMasuk::where('unit_asal', $unit->nama_unit)->exists();
        $dipakaiDiArsip      = \App\Models\Arsip::where('unit_pengolah', $unit->nama_unit)->exists();
        $dipakaiDiMonitoring = \App\Models\LogAktivitas::where('unit_kerja', $unit->nama_unit)->exists();

        if ($dipakaiDiArsipMasuk || $dipakaiDiArsip || $dipakaiDiMonitoring) {
            return redirect()->route('manajemen-unit.index')->withErrors([
                'Gagal dihapus! Data unit "' . $unit->nama_unit . '" sudah digunakan dalam transaksi.'
            ]);
        }

        $unit->delete();
        return redirect()->route('manajemen-unit.index')->with('success', 'Unit berhasil dihapus permanen!');
    }
}
