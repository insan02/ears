<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimaPContent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Cast kolom JSON menjadi Array otomatis
    protected $casts = [
        'kesepakatan' => 'array',
        'pembagian_area' => 'array',
        'struktur' => 'array',
        'visi_misi' => 'array',
        'jadwal_kegiatan' => 'array',
    ];

    public function kaizens()
    {
        return $this->hasMany(LimaPKaizen::class);
    }
}
