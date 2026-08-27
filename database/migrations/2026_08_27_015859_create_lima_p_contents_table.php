<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lima_p_contents', function (Blueprint $table) {
            $table->id();
            $table->longText('kesepakatan')->nullable();
            $table->longText('pembagian_area')->nullable();
            $table->longText('struktur')->nullable();
            $table->longText('visi_misi')->nullable();
            $table->longText('jadwal_kegiatan')->nullable();
            $table->longText('kaizen')->nullable();
            $table->string('pic')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lima_p_contents');
    }
};
