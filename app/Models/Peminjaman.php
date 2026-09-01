<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $guarded = ['id'];

    public function details()
    {
        return $this->hasMany(DetailPeminjaman::class, 'peminjaman_id');
    }

    // Helper Lama agar codingan index tidak error
    public function getArsipAttribute()
    {
        return $this->details->first()?->arsip;
    }

    public function getNamaArsipManualAttribute()
    {
        return $this->details->first()?->nama_arsip;
    }

    public function getJenisDokumenAttribute()
    {
        $detail = $this->details->first();
        if (!$detail) return '-';
        return $detail->jenis_arsip . ($detail->detail_fisik ? ' - ' . $detail->detail_fisik : '');
    }
}
