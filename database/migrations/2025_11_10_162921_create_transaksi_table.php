<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->unsignedBigInteger('id_pelanggan')->nullable();
            $table->foreign('id_pelanggan')->references('id')->on('users')->onDelete('cascade'); 
            $table->date('tgl_transaksi'); 
            $table->enum('status_pembayaran', ['Pending', 'Lunas'])->default('Pending');
            $table->string('metode_pembayaran')->nullable(); 
            $table->text('catatan')->nullable(); 
            $table->decimal('ongkir', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
