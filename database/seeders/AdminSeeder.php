<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
