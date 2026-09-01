<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    // $timestamps = false dihapus karena butuh record waktu kerja di sistem

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function arsipMasuk()
    {
        return $this->belongsTo(ArsipMasuk::class, 'arsip_masuk_id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatMonitoring::class, 'log_aktivitas_id')->orderBy('created_at', 'desc');
    }
}
