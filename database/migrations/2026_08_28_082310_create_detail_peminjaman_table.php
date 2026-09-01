<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('detail_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjaman_id');
            $table->unsignedBigInteger('arsip_id')->nullable();
            $table->string('nama_arsip')->nullable();
            $table->string('no_arsip')->nullable();
            $table->string('no_box')->nullable();
            $table->string('hak_akses')->default('Biasa');
            $table->string('jenis_arsip')->default('Softfile');
            $table->string('detail_fisik')->nullable();
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('id')->on('peminjaman')->onDelete('cascade');
            $table->foreign('arsip_id')->references('id')->on('arsip')->onDelete('set null');
        });
    }

    public function down() {
        Schema::dropIfExists('detail_peminjaman');
    }
};
