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
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('user')->onDelete('restrict');

            $table->char('kode', 15)->unique();
            $table->date('tanggal');
            $table->unsignedInteger('total_refund');
            $table->string('metode_refund');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('retur');
    }
};
