<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang')->onDelete('restrict');;
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('restrict');;
            $table->unsignedInteger('harga_beli');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};
