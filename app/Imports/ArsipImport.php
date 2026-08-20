<?php

namespace App\Imports;

use App\Models\Arsip;
use App\Models\MasterKlasifikasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ArsipImport implements ToModel, WithStartRow
{
    private $lastNoBerkas = null;
    private $lastNamaBerkas = null;
    private $lastKodeKlasifikasi = null;
    private $lastKlasifikasiId = null;
    private $lastUnit = null;
    private $lastBox = null;
    private $lastHakAkses = null;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // ----------------------------------------------------
        // PEMETAAN KOLOM BERDASARKAN FILE EXCEL ASLI:
        // 0: No (No Berkas)
        // 1: Kode Klasifikasi (HK.00.02)
        // 2: Nama Berkas (Risalah Rapat...)
        // 3: Tahun (1986, 1989, ...)
        // 4: Isi Berkas / Uraian
        // 5: Tanggal Masuk
        // 6: Jumlah
        // 7: Asli / Copy
        // 8: Masa Simpan (5 Tahun)
        // 9: Status / Tindakan (Permanen)
        // 10: Hak Akses (Terbatas)
        // 11: Lokasi (Ruangan A)
        // 12: Unit Kerja (PROYEK IIIC)
        // 13: Rak
        // 14: Tingkat
        // 15: No. Boks (1, 2)
        // ----------------------------------------------------

        $isi = trim($row[4] ?? '');
        $noBerkasRaw = trim($row[0] ?? '');
        $kodeKlasRaw = trim($row[1] ?? '');
        $namaBerkasRaw = trim($row[2] ?? '');

        // 1. Lewati baris kosong atau jika tidak sengaja membaca baris judul
        if (empty($isi) && empty($noBerkasRaw) && empty($kodeKlasRaw)) {
            return null;
        }

        if (strtolower($isi) == 'uraian' || strtolower($namaBerkasRaw) == 'nama berkas') {
            return null;
        }

        // 2. Logika Fill-Down (Pengelompokan Berkas Induk & Sub-item)
        if (!empty($noBerkasRaw)) {
            $this->lastNoBerkas = $noBerkasRaw;
            $this->lastKodeKlasifikasi = $kodeKlasRaw;
            $this->lastNamaBerkas = $namaBerkasRaw;
            $this->lastUnit = trim($row[12] ?? '-');
            $this->lastBox = trim($row[15] ?? '-');
            $this->lastHakAkses = trim($row[10] ?? 'Biasa');

            // Cari ID Klasifikasi
            $this->lastKlasifikasiId = $this->resolveKlasifikasiId($kodeKlasRaw);
        }

        $noBerkas = !empty($noBerkasRaw) ? $noBerkasRaw : $this->lastNoBerkas;
        $namaBerkas = !empty($namaBerkasRaw) ? $namaBerkasRaw : $this->lastNamaBerkas;
        $kodeKlasifikasi = !empty($kodeKlasRaw) ? $kodeKlasRaw : $this->lastKodeKlasifikasi;
        $klasifikasiId = !empty($kodeKlasRaw) ? $this->resolveKlasifikasiId($kodeKlasRaw) : $this->lastKlasifikasiId;

        $unitPengolah = !empty($row[12]) ? trim($row[12]) : ($this->lastUnit ?: '-');
        $noBox = !empty($row[15]) ? trim($row[15]) : ($this->lastBox ?: '-');
        $hakAkses = !empty($row[10]) ? trim($row[10]) : ($this->lastHakAkses ?: 'Biasa');

        // 3. User Pembuat
        $user = Auth::user() ?: \App\Models\User::first();
        $userId = $user ? $user->id : 1;

        // 4. Buat Record Arsip
        return new Arsip([
            'no_berkas'      => $noBerkas ?: '1',
            'klasifikasi_id' => $klasifikasiId,
            'nama_berkas'    => $namaBerkas ?: 'Tanpa Nama Berkas',
            'isi'            => $isi ?: ($namaBerkas ?: '-'),
            'tahun'          => is_numeric($row[3] ?? null) ? $row[3] : date('Y'),
            'tanggal_masuk'  => $this->parseDate($row[5] ?? null),
            'jumlah'         => is_numeric($row[6] ?? null) ? (int)$row[6] : 1,
            'jenis_media'    => !empty($row[7]) ? trim($row[7]) : 'Hardfile',
            'masa_simpan'    => !empty($row[8]) ? trim($row[8]) : '-',
            'tindakan_akhir' => !empty($row[9]) ? trim($row[9]) : 'Permanen',
            'hak_akses'      => $hakAkses,
            'no_box'         => $noBox,
            'unit_pengolah'  => $unitPengolah,
            'user_id'        => $userId
        ]);
    }

    /**
     * Data di file Excel Anda mulai pada Baris ke-11
     */
    public function startRow(): int
    {
        return 11;
    }

    /**
     * Helper pencari Klasifikasi ID dengan sistem fallback agar tidak pernah NULL
     */
    private function resolveKlasifikasiId($kode)
    {
        if (empty($kode)) {
            $default = MasterKlasifikasi::first();
            return $default ? $default->id : 1;
        }

        // 1. Cari exact match
        $klasifikasi = MasterKlasifikasi::where('kode_klasifikasi', $kode)->first();
        if ($klasifikasi) return $klasifikasi->id;

        // 2. Cari like match
        $klasifikasi = MasterKlasifikasi::where('kode_klasifikasi', 'like', $kode . '%')->first();
        if ($klasifikasi) return $klasifikasi->id;

        // 3. Cari berdasarkan induk (misal HK.00 dari HK.00.02)
        $parts = explode('.', $kode);
        if (count($parts) >= 2) {
            $parentCode = $parts[0] . '.' . $parts[1];
            $klasifikasi = MasterKlasifikasi::where('kode_klasifikasi', 'like', $parentCode . '%')->first();
            if ($klasifikasi) return $klasifikasi->id;
        }

        // 4. Fallback ke ID pertama yang ada di database
        $default = MasterKlasifikasi::first();
        return $default ? $default->id : 1;
    }

    private function parseDate($value)
    {
        if (!$value) return null;
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
