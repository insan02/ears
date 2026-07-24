<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->unsignedBigInteger('arsip_masuk_id')->nullable()->change();
            $table->string('nba')->nullable()->change();
            $table->string('unit_kerja')->nullable()->change();
            $table->integer('jumlah_box')->nullable()->change();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->unsignedBigInteger('arsip_masuk_id')->nullable(false)->change();
            $table->string('nba')->nullable(false)->change();
            $table->string('unit_kerja')->nullable(false)->change();
            $table->integer('jumlah_box')->nullable(false)->change();
        });
        Schema::enableForeignKeyConstraints();
    }
};
