<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        // 1. Definisikan aturan validasi dan pesan error kustom
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',  // Minimal 8 karakter
                'max:16', // Maksimal 16 karakter
                // Regex: Wajib huruf kecil, besar, simbol tertentu.
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&*!?])[A-Za-z\d@#$%^&*!?]+$/'
            ],
        ], [
            // Pesan error kustom
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.max' => 'Password tidak boleh lebih dari 16 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan minimal satu simbol yang diizinkan (@, #, $, %, ^, &, *, !, ?). Karakter lain/spasi tidak diperbolehkan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password baru.'
        ]);

        // 2. Proses reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
