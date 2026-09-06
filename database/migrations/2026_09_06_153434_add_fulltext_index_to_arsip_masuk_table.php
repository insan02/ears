<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('arsip_masuk', function (Blueprint $table) {
            // Membuat Index Fulltext agar query tidak membebani CPU server
            $table->fullText(['nomor_berita_acara', 'unit_asal']);
        });
    }

    public function down()
    {
        Schema::table('arsip_masuk', function (Blueprint $table) {
            $table->dropFullText(['nomor_berita_acara', 'unit_asal']);
        });
    }
};
