<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('arsip_masuk_id')->nullable();
            $table->enum('tahapan', ['Pemilahan', 'Pendataan', 'Pelabelan', 'Alih Media', 'Input E-Arsip']);
            $table->date('tanggal_kerja');
            $table->string('unit_kerja', 100);
            $table->string('nba', 50);
            $table->integer('jumlah_box')->default(0);
            $table->integer('jumlah_box_selesai')->default(0);
            $table->string('keterangan')->nullable();
            $table->string('status_kerja', 50)->default('Proses');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('arsip_masuk_id')->references('id')->on('arsip_masuk')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('log_aktivitas');
    }
};
