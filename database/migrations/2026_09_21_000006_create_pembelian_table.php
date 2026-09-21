<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->char('kode', 15)->unique();
            $table->integer('total');
            $table->timestamps();

            $table->foreignId('pemasok_id')->constrained('pemasok');
            $table->foreignId('user_id')->constrained('user');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
