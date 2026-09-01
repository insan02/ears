<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('arsip_musnah', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('arsip_masuk_id')->nullable();
            $table->integer('no_berkas')->nullable();
            $table->unsignedInteger('klasifikasi_id')->nullable();
            $table->string('hak_akses', 100)->nullable();
            $table->string('nama_berkas')->nullable();
            $table->text('isi')->nullable();
            $table->string('jenis_media', 100)->nullable();
            $table->year('tahun')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('masa_simpan', 100)->nullable();
            $table->string('tindakan_akhir', 100)->nullable();
            $table->string('no_box', 50)->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('unit_pengolah')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('rak', 100)->nullable();
            $table->string('tingkat', 100)->nullable();
            $table->string('asli_copy', 100)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('arsip_musnah');
    }
};
