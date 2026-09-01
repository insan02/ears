<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';
    protected $guarded = ['id'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }

    public function getNamaBerkasAttribute()
    {
        return $this->arsip ? $this->arsip->nama_berkas : $this->nama_arsip;
    }
}
