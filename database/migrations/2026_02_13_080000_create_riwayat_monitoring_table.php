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
        Schema::create('riwayat_monitoring', function (Blueprint $table) {
            $table->id();

            // log_aktivitas is int(11) (signed)
            $table->integer('log_aktivitas_id');

            // users is int(11) (signed)
            $table->integer('user_id');

            $table->string('tahapan');
            $table->date('tanggal_kerja');
            $table->integer('jumlah_box_selesai');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Add foreign keys after table creation to isolate issues
            $table->foreign('log_aktivitas_id')->references('id')->on('log_aktivitas')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_monitoring');
    }
};
