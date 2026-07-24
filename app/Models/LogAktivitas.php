<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    public $timestamps = false; // Disable timestamps because table doesn't have created_at/updated_at

    protected $fillable = [
        'user_id',
        'arsip_masuk_id', // Menambahkan foreign key
        'tahapan',
        'tanggal_kerja', // Mengubah tanggal_berkas_masuk menjadi tanggal_kerja
        'unit_kerja',
        'nba',
        'jumlah_box',
        'jumlah_box_selesai', // Menambahkan kolom baru
        'keterangan',
        'status_kerja',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function arsipMasuk()
    {
        return $this->belongsTo(ArsipMasuk::class);
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatMonitoring::class)->orderBy('created_at', 'desc');
    }
}
