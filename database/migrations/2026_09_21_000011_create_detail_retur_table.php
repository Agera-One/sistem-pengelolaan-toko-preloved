<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_retur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan')->onDelete('restrict');;
            $table->foreignId('retur_id')->constrained('retur')->onDelete('restrict');;
        });
    }

public function down(): void
    {
        Schema::dropIfExists('detail_retur');
    }
};
