<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
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
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:64'],
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'email' => 'Format email tidak valid.',
            'max' => 'Karakter dibatasi.',
        ]);

        // 2. Cek apakah user sedang terkena limit (Brute-force protection)
        $this->ensureIsNotRateLimited($request);

        // 3. Proses Autentikasi
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            // ==========================================================
            // PENGECEKAN STATUS AKUN (AKTIF / NONAKTIF)
            // ==========================================================
            if (!Auth::user()->is_active) {
                // Jika tidak aktif, paksa logout dan hapus sesi yang baru saja dibuat
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Kembalikan ke halaman login dengan pesan error spesifik
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi Administrator.',
                ])->onlyInput('email');
            }
            // ==========================================================

            // Jika sukses dan akun AKTIF, hapus catatan kegagalan login sebelumnya
            RateLimiter::clear($this->throttleKey($request));

            // Mencegah Session Fixation Attack
            $request->session()->regenerate();

            // --- KODE PEMBATASAN REMEMBER ME 3 HARI ---
            if ($request->boolean('remember')) {
                $rememberTokenName = Auth::getRecallerName();
                $rememberCookie = request()->cookie($rememberTokenName);

                if ($rememberCookie) {
                    // Timpa cookie bawaan Laravel dengan durasi 4320 menit (3 hari)
                    Cookie::queue($rememberTokenName, $rememberCookie, 4320);
                }
            }
            // ------------------------------------------

            return redirect()->intended('/beranda');
        }

        // 4. Jika gagal (email/password salah), catat kegagalan (Hit Rate Limiter)
        RateLimiter::hit($this->throttleKey($request));

        // 5. Pesan error yang aman (Mencegah User Enumeration)
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
