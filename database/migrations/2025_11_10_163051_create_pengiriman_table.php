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
        Schema::create('pengiriman', function (Blueprint $table) {
            $table->id('id_pengiriman')->autoIncrement();
            $table->foreignId('id_transaksi')
                ->constrained('transaksi', 'id_transaksi')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->enum('status_pengiriman', ['Diproses', 'Dikirim', 'Selesai'])->default('Diproses');
            $table->date('tgl_pengiriman')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengiriman');
    }
};
