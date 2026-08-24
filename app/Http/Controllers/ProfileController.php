<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Notifications\EmailUpdatedNotification;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $oldEmail = $user->email; 

        $messages = [
            'required' => 'Isi kolom :attribute',
            'email' => 'Format email tidak valid',
            'max' => 'Maksimal :max karakter',
            'min' => 'Password minimal :min karakter',
            'unique' => ':attribute sudah terdaftar',
            'confirmed' => 'Konfirmasi password tidak cocok',
            'image' => 'File harus berupa gambar',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan minimal satu simbol yang diizinkan (@, #, $, %, ^, &, *, !, ?).',
        ];

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => [
                'nullable',
                'confirmed',
                'min:8',
                'max:16',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&*!?])[A-Za-z\d@#$%^&*!?]+$/'
            ],
        ], $messages);

        // Buat flag/penanda apakah kredensial penting berubah
        $isEmailChanged = $oldEmail !== $validated['email'];
        $isPasswordChanged = !empty($validated['password']);

        // Tambahkan $isPasswordChanged di dalam array use()
        DB::transaction(function () use ($validated, $request, $user, $isEmailChanged, $isPasswordChanged) {
            
            if ($isEmailChanged) {
                DB::table('authorized_emails')->insertOrIgnore(['email' => $validated['email']]);
            }

            $user->nama = $validated['nama'];
            $user->email = $validated['email'];

            // Handle Photo Upload
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('images/profiles'), $filename);
                $user->photo = 'images/profiles/' . $filename;
            }

            // Handle Password Update
            if ($isPasswordChanged) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();
        });

        // Kirim notifikasi jika email berubah
        if ($isEmailChanged) {
            $user->notify(new EmailUpdatedNotification());
        }

        // LOGIKA BARU: Tentukan pesan spesifik berdasarkan apa yang diubah
        $logoutMessage = '';
        if ($isEmailChanged && $isPasswordChanged) {
            $logoutMessage = 'Email dan Password berhasil diubah. Silakan login kembali demi keamanan.';
        } elseif ($isEmailChanged) {
            $logoutMessage = 'Email berhasil diubah. Silakan login kembali demi keamanan.';
        } elseif ($isPasswordChanged) {
            $logoutMessage = 'Password berhasil diubah. Silakan login kembali demi keamanan.';
        }

        // Jika email atau password berubah, paksa logout
        if ($isEmailChanged || $isPasswordChanged) {
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Kirim pesan spesifik menggunakan session 'success'
            return redirect()->route('login')->with('success', $logoutMessage);
        }

        // Jika hanya ganti nama atau foto profil, tetap di halaman edit
        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}