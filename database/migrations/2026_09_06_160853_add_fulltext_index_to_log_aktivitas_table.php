<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Hapus 'tahapan' dari array, sisakan nba dan unit_kerja
            $table->fullText(['nba', 'unit_kerja'], 'log_aktivitas_fulltext_index');
        });
    }

    public function down()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->dropIndex('log_aktivitas_fulltext_index');
        });
    }
};
