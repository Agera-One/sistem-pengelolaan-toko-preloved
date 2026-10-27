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
            $table->char('kode', 15)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('pengembalian_dana');
            $table->timestamps();

            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur');
    }
};
