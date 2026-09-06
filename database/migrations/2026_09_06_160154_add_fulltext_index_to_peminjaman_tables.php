<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->fullText(['nama_peminjam', 'nip', 'unit_peminjam', 'jabatan_peminjam', 'keperluan'], 'peminjaman_fulltext_index');
        });

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->fullText(['nama_arsip', 'no_box'], 'detail_peminjaman_fulltext_index');
        });
    }

    public function down()
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropIndex('peminjaman_fulltext_index');
        });

        Schema::table('detail_peminjaman', function (Blueprint $table) {
            $table->dropIndex('detail_peminjaman_fulltext_index');
        });
    }
};
