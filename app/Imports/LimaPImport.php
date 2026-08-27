<?php

namespace App\Imports;

use App\Models\LimaPContent;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LimaPImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Jika ada ID dan datanya exist, kita update. Jika tidak, buat baru.
        if (isset($row['id']) && LimaPContent::find($row['id'])) {
            $item = LimaPContent::find($row['id']);
            $item->update([
                'pic' => $row['pic_person_in_charge'] ?? $item->pic,
                'kesepakatan' => $row['kesepakatan'] ?? $item->kesepakatan,
                'visi_misi' => $row['visi_misi'] ?? $item->visi_misi,
                'pembagian_area' => $row['pembagian_area'] ?? $item->pembagian_area,
                'struktur' => $row['struktur'] ?? $item->struktur,
                'jadwal_kegiatan' => $row['jadwal_kegiatan'] ?? $item->jadwal_kegiatan,
                'kaizen' => $row['kaizen'] ?? $item->kaizen,
            ]);
            return null;
        }

        return new LimaPContent([
            'pic' => $row['pic_person_in_charge'] ?? null,
            'kesepakatan' => $row['kesepakatan'] ?? null,
            'visi_misi' => $row['visi_misi'] ?? null,
            'pembagian_area' => $row['pembagian_area'] ?? null,
            'struktur' => $row['struktur'] ?? null,
            'jadwal_kegiatan' => $row['jadwal_kegiatan'] ?? null,
            'kaizen' => $row['kaizen'] ?? null,
        ]);
    }
}
