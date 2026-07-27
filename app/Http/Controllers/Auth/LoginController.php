<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        // 1. Validasi Input
        // 1. Validasi Input
        $credentials = $request->validate([
            // Batasi email maksimal 255 karakter
            'email' => ['required', 'email', 'max:50'],
            // Batasi password maksimal 64 atau 255 karakter (64 disarankan untuk mencegah bcrypt DoS)
            'password' => ['required', 'string', 'max:20'],
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'max' => 'Karakter dibatasi.', // Tambahan pesan error jika melewati batas
        ]);

        // 2. Cek apakah user sedang terkena limit (Brute-force protection)
        $this->ensureIsNotRateLimited($request);

        // 3. Proses Autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika sukses, hapus catatan kegagalan login sebelumnya
            RateLimiter::clear($this->throttleKey($request));

            // Mencegah Session Fixation Attack
            $request->session()->regenerate();

            return redirect()->intended('/beranda');
        }

        // 4. Jika gagal, catat kegagalan (Hit Rate Limiter)
        RateLimiter::hit($this->throttleKey($request));

        // 5. Pesan error yang aman (Mencegah User Enumeration)
        // Jangan beri tahu apakah 'email tidak ditemukan' atau 'password salah'
        return back()->withErrors([
            'email' => 'Kredensial yang Anda masukkan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Memastikan request tidak melebihi batas percobaan login.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        // Maksimal 5 kali percobaan gagal
        if (! RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
        ]);
    }

    /**
     * Membuat kunci unik untuk rate limiter berdasarkan Email dan IP Address.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }
}
