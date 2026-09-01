<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';
    protected $guarded = ['id'];
    // Timestamps sudah aktif (dihapus tulisan public $timestamps = false;)

    // Relasi ke Klasifikasi
    public function klasifikasi()
    {
        return $this->belongsTo(MasterKlasifikasi::class, 'klasifikasi_id');
    }

    // Relasi ke Arsip Masuk (Berita Acara)
    public function arsipMasuk()
    {
        return $this->belongsTo(ArsipMasuk::class, 'arsip_masuk_id');
    }

    // Relasi ke User pembuat/PIC
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Detail Peminjaman
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'arsip_id');
    }
}
