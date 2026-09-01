<?php

namespace App\Imports;

use App\Models\LimaPContent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LimaPImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Abaikan pengecekan ID dan selalu buat data baru (Insert/Append)
        // Pastikan nama key array ($row['...']) sesuai dengan nama header di Excel Anda (biasanya huruf kecil dan spasi diganti underscore)

        return new LimaPContent([
            'pic'             => $row['pic_person_in_charge'] ?? null,
            'kesepakatan'     => $row['kesepakatan'] ?? null,
            'visi_misi'       => $row['visi_misi'] ?? null,
            'pembagian_area'  => $row['pembagian_area'] ?? null,
            'struktur'        => $row['struktur'] ?? null,
            'jadwal_kegiatan' => $row['jadwal_kegiatan'] ?? null,
            'kaizen'          => $row['kaizen'] ?? null,
        ]);
    }
}
