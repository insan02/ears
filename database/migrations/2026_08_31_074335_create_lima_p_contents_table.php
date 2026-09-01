<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lima_p_contents', function (Blueprint $table) {
            $table->id();
            $table->string('pic');
            $table->json('kesepakatan')->nullable();
            $table->json('pembagian_area')->nullable();
            $table->json('struktur')->nullable();
            $table->json('visi_misi')->nullable();
            $table->json('jadwal_kegiatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lima_p_contents');
    }
};
