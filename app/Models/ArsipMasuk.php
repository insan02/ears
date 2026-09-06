<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable; // <-- 1. Import class ini

class ArsipMasuk extends Model
{
    use HasFactory, Searchable; // <-- 2. Gunakan trait Searchable

    protected $table = 'arsip_masuk';
    protected $guarded = ['id'];

    // Relasi ke User
    public function penerima()
    {
        return $this->belongsTo(User::class, 'user_penerima');
    }

    // Relasi ke Log Aktivitas
    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'arsip_masuk_id');
    }

    /**
     * 3. Konfigurasi data yang akan di-indeks oleh Scout
     */
    public function toSearchableArray()
    {
        return [
            'nomor_berita_acara' => $this->nomor_berita_acara,
            'unit_asal' => $this->unit_asal,
            // Kita bisa mendaftarkan nama relasi di sini agar ikut ter-indeks
            'nama_penerima' => $this->penerima ? $this->penerima->nama : '',
        ];
    }
}
