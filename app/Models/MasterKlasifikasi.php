<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKlasifikasi extends Model
{
    use HasFactory;

    protected $table = 'master_klasifikasi';
    public $timestamps = false; // Di migration tidak ada timestamps

    protected $guarded = ['id'];

    public function arsip()
    {
        return $this->hasMany(Arsip::class, 'klasifikasi_id');
    }
}
