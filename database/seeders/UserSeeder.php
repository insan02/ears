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
        // 1 akun administrator
        $adminEmail = 'insannurul005@gmail.com';
        $adminNama = 'Nurul Insan';

        // Buat password acak
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

        // Kirim kredensial melalui email
        try {
            $pesan = "Selamat datang di Sistem Record Center PT Semen Padang.\n\n"
                   . "Akun Administrator Anda telah berhasil dibuat. "
                   . "Berikut adalah kredensial login Anda:\n\n"
                   . "Email: $adminEmail\n"
                   . "Password: $rawPassword\n\n"
                   . "Harap segera ubah password ini di menu Profil Anda demi keamanan sistem.";

            Mail::raw($pesan, function ($message) use ($adminEmail) {
                $message->to($adminEmail)
                        ->subject('Kredensial Akun Administrator - E-Arsip Semen Padang');
            });

            $this->command->info(
                "[✓] Sukses: Email kredensial dikirim ke $adminEmail"
            );

        } catch (\Exception $e) {

            $this->command->error(
                "[✗] Gagal mengirim email ke $adminEmail. (SMTP belum disetel)"
            );

            $this->command->warn(
                "    => PASSWORD UNTUK $adminEmail ADALAH : " . $rawPassword
            );
        }

        $this->command->line(
            "------------------------------------------------------------------"
        );

        $this->command->info(
            "Proses Seeding Admin Selesai!"
        );
    }
}
