<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LimaPContent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Exports\LimaPExport;
use App\Imports\LimaPImport;
use Maatwebsite\Excel\Facades\Excel;

class LimaPController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                // Daftar fungsi yang boleh diakses oleh User Biasa
                $allowedForUsers = ['index', 'show', 'exportExcel'];

                // Jika user sedang mengakses fungsi selain 3 di atas, cek apakah dia Admin
                if (!in_array($request->route()->getActionMethod(), $allowedForUsers)) {
                    if (Auth::user()->role !== 'admin') {
                        abort(403, 'Akses Ditolak. Fitur kelola data 5P khusus Administrator.');
                    }
                }

                return $next($request);
            }),
        ];
    }

    public function index()
    {
        // Menampilkan daftar semua data 5P
        $data = LimaPContent::orderBy('id', 'desc')->get();
        return view('limap.index', compact('data'));
    }

    public function create()
    {
        return view('limap.create');
    }

    public function store(Request $request)
    {
        LimaPContent::create($request->all());
        return redirect()->route('limap.index')->with('success', 'Data 5P baru berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $data = LimaPContent::findOrFail($id);
        return view('limap.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $item = LimaPContent::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('limap.show', $id)->with('success', 'Data Informasi 5P berhasil diperbarui!');
    }

    public function destroy($id)
    {
        LimaPContent::findOrFail($id)->delete();
        return redirect()->route('limap.index')->with('success', 'Data 5P berhasil dihapus!');
    }

    public function exportExcel()
    {
        return Excel::download(new LimaPExport, 'Data_Informasi_5P_'.date('Ymd').'.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        Excel::import(new LimaPImport, $request->file('file'));
        return redirect()->back()->with('success', 'Data 5P berhasil diimport dari Excel!');
    }

    // Untuk fungsi show(), kita beri deteksi Mode Print PDF
    public function show(Request $request, $id)
    {
        $data = LimaPContent::findOrFail($id);
        $printMode = $request->get('print') === 'true';

        return view('limap.show', compact('data', 'printMode'));
    }
}
