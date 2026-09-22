<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->char('kode', 15)->unique();
            $table->unsignedInteger('ongkir');
            $table->unsignedInteger('total');
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');;
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('restrict');;
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
