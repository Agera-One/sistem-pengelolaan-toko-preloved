<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('retur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->char('kode', 15)->unique();
            $table->unsignedInteger('kerugian_pengiriman');
            $table->string('alasan', 255);
            $table->string('tipe', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur');
    }
};
