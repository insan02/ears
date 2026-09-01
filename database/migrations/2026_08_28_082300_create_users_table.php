<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'karyawan'])->default('karyawan');
            $table->string('password');
            $table->timestamp('last_login')->nullable();
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Relasi ke email yang diotorisasi (Restrict: email tidak bisa dihapus jika user masih ada)
            $table->foreign('email')->references('email')->on('authorized_emails')->onDelete('restrict');
        });
    }

    public function down() {
        Schema::dropIfExists('users');
    }
};
