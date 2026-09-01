<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan email sudah ada di authorized_emails
        DB::table('authorized_emails')->updateOrInsert(
            ['email' => 'admin@gmail.com'],
        );

        // Buat atau update akun admin
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('password123'),
                'photo' => null,
                'last_login' => null,
                'remember_token' => null,
            ]
        );
    }
}
