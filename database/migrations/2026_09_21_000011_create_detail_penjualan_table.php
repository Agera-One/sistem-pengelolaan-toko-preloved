<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang');
            $table->foreignId('penjualan_id')->constrained('penjualan');
            $table->unsignedInteger('harga_jual');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_penjualan');
    }
};
