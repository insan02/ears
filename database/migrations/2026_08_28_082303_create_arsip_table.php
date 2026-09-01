<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('arsip_masuk_id')->nullable(); // Nullable jika input manual tanpa BA Masuk
            $table->string('no_berkas', 50)->nullable();
            $table->unsignedInteger('klasifikasi_id');
            $table->string('hak_akses')->default('Biasa');
            $table->string('nama_berkas'); // Wajib
            $table->text('isi')->nullable();
            $table->string('jenis_media')->default('Kertas');
            $table->integer('tahun')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->integer('jumlah')->default(1);
            $table->string('masa_simpan')->nullable();
            $table->string('tindakan_akhir')->nullable();
            $table->string('no_box', 50)->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->string('unit_pengolah')->nullable();
            $table->timestamps(); // Ditambahkan untuk audit

            $table->foreign('arsip_masuk_id')->references('id')->on('arsip_masuk')->onDelete('cascade');
            $table->foreign('klasifikasi_id')->references('id')->on('master_klasifikasi')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down() {
        Schema::dropIfExists('arsip');
    }
};
