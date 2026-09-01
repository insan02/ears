<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('riwayat_monitoring', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('log_aktivitas_id');
            $table->unsignedInteger('user_id');
            $table->string('tahapan');
            $table->date('tanggal_kerja');
            $table->integer('jumlah_box_selesai');
            $table->integer('jumlah_tambahan')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('log_aktivitas_id')->references('id')->on('log_aktivitas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('riwayat_monitoring');
    }
};
