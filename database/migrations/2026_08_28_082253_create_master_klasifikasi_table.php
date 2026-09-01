<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_klasifikasi', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_klasifikasi', 50)->unique();
            $table->text('jenis_arsip');
            $table->string('aktif');
            $table->string('inaktif');
            $table->string('masa_simpan');
            $table->string('tindakan_akhir');
            $table->string('hak_akses')->nullable();
        });
    }

    public function down() {
        Schema::dropIfExists('master_klasifikasi');
    }
};
