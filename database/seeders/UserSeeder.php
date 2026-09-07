<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tentukan Daftar 3 Akun Admin Anda Di Sini
        $admins = [
            ['email' => 'reni.rahmadhani@sig.id', 'nama' => 'Reni Sari Rahmadhani'],
            ['email' => 'jhon.ramadony@sig.id', 'nama' => 'Jhon Ramadony'],
            ['email' => 'hendriadi@sig.id', 'nama' => 'Hendriadi'],
        ];

        foreach ($admins as $admin) {
            $adminEmail = $admin['email'];
            $adminNama = $admin['nama'];

            // Buat Password Acak yang kuat (10 karakter string + 3 karakter wajib regex)
            $rawPassword = Str::random(10) . '@1A';

            // Pastikan email sudah ada di authorized_emails
            DB::table('authorized_emails')->updateOrInsert(
                ['email' => $adminEmail]
            );

            // Buat atau update akun admin
            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'nama' => $adminNama,
                    'role' => 'admin',
                    'password' => Hash::make($rawPassword),
                    'photo' => null,
                    'last_login' => null,
                    'remember_token' => null,
                ]
            );

            // LOGIKA PENGIRIMAN EMAIL OTOMATIS
            try {
                $pesan = "Selamat datang di Sistem Record Center PT Semen Padang.\n\n"
                       . "Akun Administrator Anda telah berhasil dibuat. Berikut adalah kredensial login Anda:\n\n"
                       . "Email: $adminEmail\n"
                       . "Password: $rawPassword\n\n"
                       . "Harap segera ubah password ini di menu Profil Anda demi keamanan sistem.";

                Mail::raw($pesan, function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                            ->subject('Kredensial Akun Administrator - E-Arsip Semen Padang');
                });

                // Pesan sukses di terminal untuk setiap email
                $this->command->info("[\u{2713}] Sukses: Email kredensial dikirim ke $adminEmail");

            } catch (\Exception $e) {
                // Jika email gagal terkirim (SMTP belum di-set), cetak di terminal server
                $this->command->error("[\u{2717}] Gagal mengirim email ke $adminEmail. (SMTP belum disetel)");
                $this->command->warn("    => PASSWORD UNTUK $adminEmail ADALAH : " . $rawPassword);
            }
        }

        $this->command->line("------------------------------------------------------------------");
        $this->command->info("Proses Seeding User Selesai! Jika ada email yang gagal terkirim, harap simpan password yang tampil di atas secara manual.");
    }
}
