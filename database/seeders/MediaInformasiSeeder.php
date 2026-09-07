<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MediaInformasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MediaInformasiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. LOGIKA AUTO-COPY FOTO DUMMY
        // Cek apakah file sumber (hp 6.jpeg) ada di folder public/images
        $sourceImage = public_path('images/hp 6.jpeg');

        if (File::exists($sourceImage)) {
            // Pastikan folder media ada di dalam storage
            Storage::disk('public')->makeDirectory('media');

            // Copy file tersebut menjadi dummy.jpg di storage/app/public/media/
            Storage::disk('public')->put('media/dummy.jpg', file_get_contents($sourceImage));
            $this->command->info('Foto dummy berhasil di-generate secara otomatis.');
        } else {
            $this->command->warn('Gambar sumber (hp 6.jpeg) tidak ditemukan, gambar mungkin akan pecah di tampilan.');
        }

        // 2. DATA BERITA DUMMY
        $dummyData = [];
        $topikBerita = [
            'Kunjungan Studi Banding Kearsipan',
            'Sosialisasi E-Arsip Tahap II',
            'Pemusnahan Arsip Tahap I Tahun 2026',
            'Audit Internal Sistem Manajemen',
            'Pelatihan Penataan Arsip Inaktif',
            'Sertifikasi ISO Kearsipan',
            'Penyerahan Arsip Statis ke ANRI',
            'Monitoring Kinerja Tim Record Center',
            'Evaluasi Vendor Alih Media',
            'Digitalisasi Dokumen Keuangan',
            'Rapat Koordinasi Tim Kearsipan',
            'Pembaruan Klasifikasi Arsip (JRA)',
            'Pengecekan Rutin Keamanan Arsip',
            'Integrasi Sistem E-Arsip Semen Padang',
            'Penghargaan Tata Kelola Arsip Terbaik'
        ];

        for ($i = 0; $i < 15; $i++) {
            $dummyData[] = [
                'judul' => $topikBerita[$i],
                // Tanggal dibuat acak mundur dari hari ini (1 hingga 60 hari yang lalu)
                'tanggal' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d'),
                // Deskripsi diatur agar tidak lebih dari 100 karakter
                'deskripsi' => "Ini adalah deskripsi singkat untuk kegiatan " . strtolower($topikBerita[$i]) . " di lingkungan PT Semen Padang.",
                // Memanggil file dummy yang di-generate di atas
                'gambar' => json_encode(['media/dummy.jpg']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Bersihkan tabel sebelum diisi (opsional, agar tidak menumpuk jika dijalankan 2x)
        MediaInformasi::truncate();

        // Simpan ke database
        MediaInformasi::insert($dummyData);

        $this->command->info('15 Data Dummy Media Informasi berhasil ditambahkan!');
    }
}
