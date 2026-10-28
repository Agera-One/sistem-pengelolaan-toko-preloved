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
            $table->foreignId('pakaian_id')->constrained('pakaian')->onDelete('restrict');
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('cascade');
            $table->foreignId('pengiriman_id')->constrained('pengiriman')->onDelete('restrict')->nullable();

            $table->unsignedInteger('harga_jual');
        });
    }

public function down(): void
    {
        Schema::dropIfExists('detail_retur');
    }
};
