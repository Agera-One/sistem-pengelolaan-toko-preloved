<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pakaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelian')->onDelete('restrict');

            $table->char('kode', 15)->unique();
            $table->string('nama');
            $table->unsignedInteger('lingkar');
            $table->unsignedInteger('panjang');
            $table->string('kategori');
            $table->unsignedInteger('harga_beli');
            $table->unsignedInteger('harga_jual');
            $table->string('status')->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pakaian');
    }
};
