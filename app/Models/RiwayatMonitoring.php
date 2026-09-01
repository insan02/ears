<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatMonitoring extends Model
{
    use HasFactory;

    protected $table = 'riwayat_monitoring';
    protected $guarded = ['id'];

    public function logAktivitas()
    {
        return $this->belongsTo(LogAktivitas::class, 'log_aktivitas_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
