<?php

namespace App\Imports;

use App\Models\Arsip;
use App\Models\MasterKlasifikasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class ArsipImport implements ToModel, WithStartRow, WithChunkReading, WithBatchInserts
{
    // Tambahkan variabel untuk tracking
    private static $processedCount = 0;
    private $importId;

    // Terima ID Import dari Controller
    public function __construct($importId = null)
    {
        $this->importId = $importId;
        self::$processedCount = 0; // Reset counter saat kelas dipanggil
    }

    public function model(array $row)
    {
        // 1. HITUNG BERAPA KOLOM YANG TERISI DI BARIS INI
        $filledCount = 0;
        $mergedText = '';

        for ($i = 0; $i <= 15; $i++) {
            $val = trim($row[$i] ?? '');
            if ($val !== '') {
                $filledCount++;
                // Ambil teks dari kolom yang terisi
                $mergedText = $val;
            }
        }

        // Jika kosong semua, abaikan
        if ($filledCount === 0) {
            return null;
        }

        // Abaikan baris header / judul kolom bawaan dari Excel
        $namaBerkas = trim($row[2] ?? '');
        $isi = trim($row[4] ?? '');
        if (strtolower($namaBerkas) == 'nama berkas' || strtolower($isi) == 'isi berkas' || strtolower($isi) == 'uraian') {
            return null;
        }

        $user = Auth::user() ?: \App\Models\User::first();
        $userId = $user ? $user->id : 1;

        // --- PROGRESS TRACKING ---
        self::$processedCount++;
        if ($this->importId && self::$processedCount % 500 === 0) {
            Cache::put('import_arsip_progress_' . $this->importId, self::$processedCount, 3600);
        }

        // ==============================================================
        // KASUS KHUSUS: HANYA 1 KOLOM SAJA YANG TERISI DI BARIS TERSEBUT
        // ==============================================================
        if ($filledCount === 1) {
            return new Arsip([
                'no_berkas'      => '__MERGED_ROW__', // Tanda khusus untuk baris merge
                'klasifikasi_id' => 1,
                'nama_berkas'    => $mergedText,
                'isi'            => '-',
                'tahun'          => null,
                'tanggal_masuk'  => null,
                'jumlah'         => 1,
                'jenis_media'    => '-',
                'masa_simpan'    => '-',
                'tindakan_akhir' => '-',
                'hak_akses'      => '-',
                'unit_pengolah'  => '-',
                'no_box'         => '-',
                'user_id'        => $userId
            ]);
        }

        // ==============================================================
        // LOGIKA NORMAL: DATA ARSIP ASLI (BANYAK KOLOM TERISI)
        // ==============================================================
        $noBerkas = trim($row[0] ?? '');
        $kodeKlas = trim($row[1] ?? '');

        $klasifikasiId = 1;
        if ($kodeKlas !== '') {
            $klasifikasiId = $this->resolveKlasifikasiId($kodeKlas);
        }

        $jumlahRaw = trim($row[6] ?? '');
        $jumlahFinal = 1;
        if ($jumlahRaw !== '') {
            preg_match('/\d+/', $jumlahRaw, $matches);
            if (!empty($matches[0])) {
                $jumlahFinal = (int)$matches[0];
            }
        }

        $tahunRaw = trim($row[3] ?? '');
        $tahunFinal = null;
        if (preg_match('/\d{4}/', $tahunRaw, $matches)) {
            $tahunFinal = (int)$matches[0];
        }

        $hakAksesRaw = trim($row[10] ?? '') ?: '-';
        $jenisMediaRaw = trim($row[7] ?? '') ?: '-';
        if ($jenisMediaRaw === '-') {
            $jenisMediaRaw = trim($row[10] ?? '') ?: '-';
        }
        $masaSimpanRaw = trim($row[8] ?? '') ?: '-';
        $tindakanAkhirRaw = trim($row[9] ?? '') ?: '-';
        $unitPengolahRaw = trim($row[12] ?? '') ?: '-';
        $noBoxRaw = trim($row[15] ?? '') ?: '-';

        return new Arsip([
            'no_berkas'      => $noBerkas ?: '-',
            'klasifikasi_id' => $klasifikasiId,
            'nama_berkas'    => $namaBerkas ?: '-',
            'isi'            => $isi ?: '-',
            'tahun'          => $tahunFinal,
            'tanggal_masuk'  => $this->parseDate($row[5] ?? null),
            'jumlah'         => $jumlahFinal,
            'jenis_media'    => $jenisMediaRaw,
            'masa_simpan'    => $masaSimpanRaw,
            'tindakan_akhir' => $tindakanAkhirRaw,
            'hak_akses'      => $hakAksesRaw,
            'unit_pengolah'  => $unitPengolahRaw,
            'no_box'         => $noBoxRaw,
            'user_id'        => $userId
        ]);
    }

    public function startRow(): int { return 11; }
    public function chunkSize(): int { return 500; }
    public function batchSize(): int { return 500; }

    private function resolveKlasifikasiId($kode)
    {
        $klasifikasi = MasterKlasifikasi::where('kode_klasifikasi', $kode)->first();
        if ($klasifikasi) return $klasifikasi->id;

        $klasifikasi = MasterKlasifikasi::where('kode_klasifikasi', 'like', $kode . '%')->first();
        if ($klasifikasi) return $klasifikasi->id;

        $default = MasterKlasifikasi::first();
        return $default ? $default->id : 1;
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        try {
            if (is_numeric($value)) return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            return Carbon::parse($value);
        } catch (\Throwable $e) { return null; }
    }
}
