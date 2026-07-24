<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatMonitoring extends Model
{
    use HasFactory;

    protected $table = 'riwayat_monitoring';

    protected $fillable = [
        'log_aktivitas_id',
        'user_id',
        'tahapan',
        'tanggal_kerja',
        'jumlah_box_selesai',
        'keterangan',
    ];

    public function logAktivitas()
    {
        return $this->belongsTo(LogAktivitas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
