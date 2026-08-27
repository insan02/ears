<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LimaPContent;

class LimaPSeeder extends Seeder
{
    public function run()
    {
        $dummies = [
            [
                'pic' => 'Budi Santoso - Kepala Gudang Area A',
                'kesepakatan' => "1. Membuang sampah pada tempatnya sesuai jenis (Organik/Non-Organik).\n2. Mengembalikan alat kerja ke *shadow board* setelah digunakan.\n3. Melakukan pembersihan area 5 menit sebelum jam pulang.",
                'visi_misi' => "VISI:\nMewujudkan Area Gudang A yang Bersih, Rapi, dan Aman untuk operasional tanpa hambatan.\n\nMISI:\n1. Mengimplementasikan budaya 5P setiap hari.\n2. Mengurangi waktu pencarian barang (Zero Searching Time).",
                'pembagian_area' => "Zona 1 (Merah): Area Barang Masuk (Inbound)\nZona 2 (Kuning): Area Penyimpanan Utama\nZona 3 (Hijau): Area Barang Keluar (Outbound)\nZona 4 (Biru): Area Istirahat & Peralatan",
                'struktur' => "Penanggung Jawab: Budi Santoso\nAuditor Internal: Siti Aminah\nAnggota:\n- Andi (Zona 1)\n- Cici (Zona 2)\n- Dedi (Zona 3)",
                'jadwal_kegiatan' => "Senin: Fokus Pemilahan (Memisahkan barang rusak/usang)\nRabu: Fokus Penataan (Merapikan label rak)\nJumat: Pembersihan Total (Jumat Bersih 15 Menit)\nAkhir Bulan: Audit 5P Internal",
                'kaizen' => "Bulan Ini: Membuat *Shadow Board* (Papan Bayangan) untuk alat kebersihan agar sapu dan pel tidak berserakan dan hilang."
            ],
            [
                'pic' => 'Agus Rahman - Workshop Maintenance',
                'kesepakatan' => "1. Oli atau cairan yang tumpah wajib langsung dibersihkan.\n2. Wajib menggunakan APD saat berada di area Workshop.",
                'visi_misi' => "VISI:\nWorkshop handal dengan zero accident.\n\nMISI:\n1. Menjaga kebersihan alat berat.\n2. Penataan sparepart yang presisi.",
                'pembagian_area' => "Area A: Mesin Bubut\nArea B: Pengelasan (Welding)\nArea C: Penyimpanan Sparepart",
                'struktur' => "Ketua: Agus Rahman\nAnggota:\n- Riki (Welding)\n- Faisal (Mesin)",
                'jadwal_kegiatan' => "Setiap Pagi: Briefing Keselamatan & 5P\nKamis: Inspeksi kelayakan mesin.",
                'kaizen' => "Bulan Ini: Penambahan laci kecil modular untuk baut dan mur berukuran di bawah 5mm agar tidak tercampur."
            ]
        ];

        foreach ($dummies as $dummy) {
            LimaPContent::create($dummy);
        }
    }
}
