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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id('id_detail')->autoIncrement();
            $table->foreignId('id_transaksi')
                ->constrained('transaksi', 'id_transaksi')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('id_produk')
                ->constrained('produk', 'id_produk')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
