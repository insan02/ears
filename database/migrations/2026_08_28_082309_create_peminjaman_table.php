<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pinjam');
            $table->string('nama_peminjam');
            $table->string('nip');
            $table->string('unit_peminjam');
            $table->string('jabatan_peminjam');
            $table->text('keperluan')->nullable();
            $table->text('bukti_peminjaman')->nullable();
            $table->enum('status', ['Sedang Dipinjam', 'Sudah Dikembalikan', 'Telat Dikembalikan'])->default('Sedang Dipinjam');
            $table->boolean('is_approved_khusus')->default(0);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('peminjaman');
    }
};
