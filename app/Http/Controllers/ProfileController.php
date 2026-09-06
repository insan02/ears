<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // <-- PERBAIKAN: Import Facade Storage
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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $oldEmail = $user->email;

        // PERBAIKAN: Pesan error disamakan dan diperjelas seperti di ResetPasswordController
        $messages = [
            'required' => 'Isi kolom :attribute.',
            'email' => 'Format email tidak valid.',
            'max' => 'Maksimal :max karakter.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            'email.max' => 'Email maksimal 255 karakter.',
            'password.min' => 'Password baru minimal harus 8 karakter.',
            'password.max' => 'Password baru tidak boleh lebih dari 16 karakter.',
            'unique' => ':attribute sudah terdaftar di sistem.',
            'confirmed' => 'Konfirmasi password tidak cocok dengan password baru.',
            'image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran foto profil tidak boleh lebih dari 2MB.',
            // Pesan Regex diperjelas
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan minimal satu simbol (@, #, $, %, ^, &, *, !, ?). Spasi dilarang.',
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
                // PERBAIKAN: Menambahkan (?=.*\d) agar password wajib mengandung minimal 1 angka
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*!?])[A-Za-z\d@#$%^&*!?]+$/'
            ],
        ], $messages);

        $isEmailChanged = $oldEmail !== $validated['email'];
        $isPasswordChanged = !empty($validated['password']);

        DB::transaction(function () use ($validated, $request, $user, $isEmailChanged, $isPasswordChanged) {

            if ($isEmailChanged) {
                DB::table('authorized_emails')->insertOrIgnore(['email' => $validated['email']]);
            }

            $user->nama = $validated['nama'];
            $user->email = $validated['email'];

            // ==========================================
            // PERBAIKAN: HANDLE UPLOAD FOTO KE STORAGE
            // ==========================================
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();

                // Hapus foto lama dari storage public (jika ada dan bukan gambar bawaan sistem)
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                // Simpan foto baru ke folder storage/app/public/profiles
                $path = $file->storeAs('profiles', $filename, 'public');
                $user->photo = $path; // Menyimpan teks 'profiles/filename.jpg' ke DB
            }

            if ($isPasswordChanged) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();
        });

        if ($isEmailChanged) {
            $user->notify(new EmailUpdatedNotification());
        }

        $logoutMessage = '';
        if ($isEmailChanged && $isPasswordChanged) {
            $logoutMessage = 'Email dan Password berhasil diubah. Silakan login kembali demi keamanan.';
        } elseif ($isEmailChanged) {
            $logoutMessage = 'Email berhasil diubah. Silakan login kembali demi keamanan.';
        } elseif ($isPasswordChanged) {
            $logoutMessage = 'Password berhasil diubah. Silakan login kembali demi keamanan.';
        }

        if ($isEmailChanged || $isPasswordChanged) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('success', $logoutMessage);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
