<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('arsip_masuk', function (Blueprint $table) {
            $table->increments('id');
            $table->string('unit_asal', 100);
            $table->date('tanggal_terima');
            $table->integer('jumlah_box_masuk');
            $table->unsignedInteger('user_penerima');
            $table->string('nomor_berita_acara', 100);
            $table->timestamps();

            $table->foreign('user_penerima')->references('id')->on('users')->onDelete('restrict');
        });
    }

    public function down() {
        Schema::dropIfExists('arsip_masuk');
    }
};
