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
        // FILTER BARIS KOSONG
        $isEmptyRow = true;
        for ($i = 0; $i <= 15; $i++) {
            if (trim($row[$i] ?? '') !== '') {
                $isEmptyRow = false;
                break;
            }
        }

        if ($isEmptyRow) return null;

        $namaBerkas = trim($row[2] ?? '');
        $isi = trim($row[4] ?? '');

        if (strtolower($namaBerkas) == 'nama berkas' || strtolower($isi) == 'isi berkas' || strtolower($isi) == 'uraian') {
            return null;
        }

        // TANGKAP DATA APA ADANYA
        $noBerkas = trim($row[0] ?? '');
        $kodeKlas = trim($row[1] ?? '');

        $klasifikasiId = 1;
        if ($kodeKlas !== '') {
            $klasifikasiId = $this->resolveKlasifikasiId($kodeKlas);
        }

        $user = Auth::user() ?: \App\Models\User::first();
        $userId = $user ? $user->id : 1;

        $jumlahRaw = trim($row[6] ?? '');
        $jumlahFinal = null;
        if ($jumlahRaw !== '') {
            preg_match('/\d+/', $jumlahRaw, $matches);
            $jumlahFinal = !empty($matches[0]) ? (int)$matches[0] : 1;
        }

        $hakAksesRaw = trim($row[10] ?? '');
        $jenisMediaRaw = trim($row[7] ?? '');
        if (empty($jenisMediaRaw)) {
            $jenisMediaRaw = trim($row[10] ?? '');
        }

        // --- UPDATE PROGRESS KE CACHE SETIAP 500 BARIS ---
        self::$processedCount++;
        if ($this->importId && self::$processedCount % 500 === 0) {
            Cache::put('import_arsip_progress_' . $this->importId, self::$processedCount, 3600);
        }

        return new Arsip([
            'no_berkas'      => $noBerkas ?: null,
            'klasifikasi_id' => $klasifikasiId,
            'nama_berkas'    => $namaBerkas ?: null,
            'isi'            => $isi ?: null,
            'tahun'          => trim($row[3] ?? '') ?: null,
            'tanggal_masuk'  => $this->parseDate($row[5] ?? null),
            'jumlah'         => $jumlahFinal,
            'jenis_media'    => trim($row[7] ?? '') ?: null,
            'masa_simpan'    => trim($row[8] ?? '') ?: null,
            'tindakan_akhir' => trim($row[9] ?? '') ?: null,
            'hak_akses'      => $hakAksesRaw ?: null,
            'unit_pengolah'  => trim($row[12] ?? '') ?: null,
            'no_box'         => trim($row[15] ?? '') ?: null,
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
