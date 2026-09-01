<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Timestamps sekarang AKTIF karena sudah ada di migration

    protected $fillable = [
        'nama',
        'email',
        'role',
        'password',
        'last_login',
        'photo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    // --- RELASI ---
    public function arsipDibuat()
    {
        return $this->hasMany(Arsip::class, 'user_id');
    }

    public function penerimaanArsip()
    {
        return $this->hasMany(ArsipMasuk::class, 'user_penerima');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }
}
