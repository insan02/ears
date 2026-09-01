<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini jika ada softDeletes

class ArsipMusnah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'arsip_musnah';
    protected $guarded = ['id'];

    public function klasifikasi()
    {
        return $this->belongsTo(MasterKlasifikasi::class, 'klasifikasi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function arsipMasuk()
    {
        return $this->belongsTo(ArsipMasuk::class, 'arsip_masuk_id');
    }
}
