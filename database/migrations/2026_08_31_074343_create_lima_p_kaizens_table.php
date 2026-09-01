<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lima_p_kaizens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lima_p_content_id')->constrained('lima_p_contents')->cascadeOnDelete();
            $table->integer('tahun');
            $table->integer('bulan');
            $table->string('file_path');
            $table->string('original_name');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('lima_p_kaizens');
    }
};
