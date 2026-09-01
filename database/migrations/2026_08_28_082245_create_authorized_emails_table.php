<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('authorized_emails', function (Blueprint $table) {
            $table->string('email')->primary();
        });
    }

    public function down() {
        Schema::dropIfExists('authorized_emails');
    }
};
