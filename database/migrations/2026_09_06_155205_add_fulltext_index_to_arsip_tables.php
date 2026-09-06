<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('arsip', function (Blueprint $table) {
            // Hapus 'no_berkas' dari array
            $table->fullText(['nama_berkas', 'isi']);
        });

        Schema::table('arsip_musnah', function (Blueprint $table) {
            $table->fullText(['nama_berkas', 'isi']);
        });
    }

    public function down()
    {
        Schema::table('arsip', function (Blueprint $table) {
            $table->dropFullText(['nama_berkas', 'isi']);
        });

        Schema::table('arsip_musnah', function (Blueprint $table) {
            $table->dropFullText(['nama_berkas', 'isi']);
        });
    }
};
