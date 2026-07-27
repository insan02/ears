<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang benar di database 'ears'
    protected $table = 'pinjaman';

    // Matikan timestamps karena di tabel kamu tidak ada kolom 'created_at' & 'updated_at'
    public $timestamps = false;

    // Daftarkan semua kolom yang boleh diisi lewat formulir
    protected $fillable = [
        'arsip_id',
        'nama_peminjam',
        'unit_peminjam',
        'tanggal_pinjam',
        'status',
    ];

    // Relasi: Setiap peminjaman "Milik" satu Arsip
    public function arsip()
    {
        return $this->belongsTo(Arsip::class, 'arsip_id');
    }
}
