<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimaPContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'kesepakatan',
        'pembagian_area',
        'struktur',
        'visi_misi',
        'jadwal_kegiatan',
        'kaizen',
        'pic'
    ];
}
