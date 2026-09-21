<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_penjualan', function (Blueprint $table) {
            $table->id();
            $table->char('kode', 15)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('jumlah');
            $table->string('metode_pembayaran');
            $table->timestamps();

            $table->foreignId('penjualan_id')->constrained('penjualan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_penjualan');
    }
};
