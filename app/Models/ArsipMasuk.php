<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArsipMasuk extends Model
{
    use HasFactory;

    protected $table = 'arsip_masuk';
    protected $guarded = ['id'];

    // Relasi ke User (Penerima)
    public function penerima()
    {
        return $this->belongsTo(User::class, 'user_penerima');
    }

    // Relasi ke Arsip
    public function arsip()
    {
        return $this->hasMany(Arsip::class, 'arsip_masuk_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'arsip_masuk_id');
    }
}
