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
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('restrict');
            
            $table->char('kode', 15)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('subtotal')->nullable();
            $table->unsignedInteger('ongkir');
            $table->unsignedInteger('total')->nullable();
            $table->string('status')->default('belum bayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
