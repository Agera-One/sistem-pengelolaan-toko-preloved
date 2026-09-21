<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->char('kode', 15)->unique();
            $table->string('nama');
            $table->integer('lingkar')->nullable();
            $table->integer('panjang')->nullable();
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->string('kategori');
            $table->string('status');
            $table->timestamps();

            $table->foreignId('pembelian_id')->nullable()->constrained('pembelian');
            $table->foreignId('penjualan_id')->nullable()->constrained('penjualan');
            $table->foreignId('retur_id')->nullable()->constrained('retur');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
