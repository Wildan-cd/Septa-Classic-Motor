<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Ubah status_pembayaran di tabel TRANSAKSI
        Schema::table('transaksi', function (Blueprint $table) {
            // Ubah dari Enum ke String agar fleksibel (Unpaid, Pending, Cancelled, dll)
            // Pastikan package doctrine/dbal sudah terinstall jika pakai Laravel versi lama
            $table->string('status_pembayaran')->default('Unpaid')->change();
        });

        // 2. Ubah status_pengiriman di tabel PENGIRIMAN
        Schema::table('pengiriman', function (Blueprint $table) {
            // Ubah default value atau tipe datanya
            $table->string('status_pengiriman')->default('Diproses')->change();
        });
    }

    public function down()
    {
        // Kembalikan ke kondisi semula jika rollback
        Schema::table('transaksi', function (Blueprint $table) {
            // Sesuaikan dengan tipe data awal Anda (misal enum)
            $table->enum('status_pembayaran', ['Pending', 'Lunas'])->default('Pending')->change();
        });

        Schema::table('pengiriman', function (Blueprint $table) {
            $table->string('status_pengiriman')->default('Diproses')->change();
        });
    }
};
