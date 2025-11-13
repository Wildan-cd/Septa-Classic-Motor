<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Data dummy untuk testing dashboard
     */
    public function run(): void
    {
        // Clear existing data
        // DB::table('detail_transaksi')->truncate();
        // DB::table('pengiriman')->truncate();
        // DB::table('transaksi')->truncate();
        // DB::table('stok')->truncate();
        // DB::table('produk')->truncate();
        // DB::table('pelanggan')->truncate();

        // Create Customers (Pelanggan)
        $pelanggan = [
            ['nama' => 'Kevin', 'no_telp' => '081234567890', 'alamat_lengkap' => 'Jl. Merpati No. 10, Malang'],
            ['nama' => 'Komael', 'no_telp' => '081234567891', 'alamat_lengkap' => 'Jl. Kenari No. 5, Batu'],
            ['nama' => 'Nikhil', 'no_telp' => '081234567892', 'alamat_lengkap' => 'Jl. Anggrek No. 15, Surabaya'],
            ['nama' => 'Shivam', 'no_telp' => '081234567893', 'alamat_lengkap' => 'Jl. Melati No. 20, Malang'],
            ['nama' => 'Shadab', 'no_telp' => '081234567894', 'alamat_lengkap' => 'Jl. Mawar No. 8, Batu'],
            ['nama' => 'Yogesh', 'no_telp' => '081234567895', 'alamat_lengkap' => 'Jl. Dahlia No. 12, Kediri'],
        ];

        foreach ($pelanggan as $customer) {
            DB::table('pelanggan')->insert($customer);
        }

        // Create Products
        $produk = [
            ['nama_produk' => 'Lampu Depan CB100', 'kategori' => 'Lampu Depan', 'harga' => 126500, 'stok' => 15, 'keterangan' => 'Lampu depan original untuk Honda CB100'],
            ['nama_produk' => 'Spakbor Depan CB125', 'kategori' => 'Spakbor', 'harga' => 85000, 'stok' => 20, 'keterangan' => 'Spakbor depan custom untuk CB125'],
            ['nama_produk' => 'Sein CB Series', 'kategori' => 'Sein', 'harga' => 45000, 'stok' => 30, 'keterangan' => 'Sein universal CB series'],
            ['nama_produk' => 'Handle Custom', 'kategori' => 'Other', 'harga' => 150000, 'stok' => 10, 'keterangan' => 'Handle stang custom chrome'],
            ['nama_produk' => 'Jok Kulit Custom', 'kategori' => 'Other', 'harga' => 350000, 'stok' => 8, 'keterangan' => 'Jok kulit custom model cafe racer'],
        ];

        foreach ($produk as $product) {
            DB::table('produk')->insert($product);
        }

        // Create Stock History
        foreach ($produk as $index => $product) {
            DB::table('stok')->insert([
                'id_produk' => $index + 1,
                'jumlah_stok' => $product['stok'],
                'tgl_update' => now()->format('Y-m-d'),
            ]);
        }

        // Create Transactions for last 6 months
        $months = [
            Carbon::create(2023, 7, 1),  // July
            Carbon::create(2023, 8, 1),  // August
            Carbon::create(2023, 9, 1),  // September
            Carbon::create(2023, 10, 1), // October
            Carbon::create(2023, 11, 1), // November
            Carbon::create(2023, 12, 1), // December
        ];
        
        $statuses = ['Diproses', 'Dikirim', 'Selesai'];
        $paymentStatuses = ['Pending', 'Lunas'];
        
        $id_transaksi = 1;
        
        foreach ($months as $month) {
            // Create 5-15 transactions per month (random)
            $numTransactions = rand(5, 15);
            
            for ($i = 0; $i < $numTransactions; $i++) {
                $customerId = rand(1, 6);
                $date = $month->copy()->addDays(rand(0, 28))->format('Y-m-d');
                
                // Payment status
                $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
                
                // Shipping status based on payment
                if ($paymentStatus === 'Pending') {
                    $shippingStatus = 'Diproses';
                } else {
                    $shippingStatus = $statuses[rand(0, 2)]; // Lunas bisa Diproses, Dikirim, atau Selesai
                }
                
                // Calculate transaction details
                $numItems = rand(1, 3);
                $subtotal = 0;
                $ongkir = rand(10, 50) * 1000;
                
                // Insert Transaction
                DB::table('transaksi')->insert([
                    'id_pelanggan' => $customerId,
                    'tgl_transaksi' => $date,
                    'status_pembayaran' => $paymentStatus,
                    'ongkir' => $ongkir,
                    'total_harga' => 0, // Will update after detail
                ]);
                
                // Insert Transaction Details
                for ($j = 0; $j < $numItems; $j++) {
                    $productId = rand(1, 5);
                    $product = DB::table('produk')->where('id_produk', $productId)->first();
                    $quantity = rand(1, 3);
                    
                    DB::table('detail_transaksi')->insert([
                        'id_transaksi' => $id_transaksi,
                        'id_produk' => $productId,
                        'jumlah' => $quantity,
                        'harga_satuan' => $product->harga,
                    ]);
                    
                    $subtotal += $product->harga * $quantity;
                }
                
                // Update total transaction
                $totalHarga = $subtotal + $ongkir;
                DB::table('transaksi')
                    ->where('id_transaksi', $id_transaksi)
                    ->update(['total_harga' => $totalHarga]);
                
                // Insert Shipping
                $shippingDate = null;
                if ($shippingStatus !== 'Diproses' && $shippingStatus !== 'Dibatalkan') {
                    $shippingDate = Carbon::parse($date)->addDays(rand(1, 5))->format('Y-m-d');
                }
                
                DB::table('pengiriman')->insert([
                    'id_transaksi' => $id_transaksi,
                    'status_pengiriman' => $shippingStatus,
                    'tgl_pengiriman' => $shippingDate,
                ]);
                
                $id_transaksi++;
            }
        }
        
        echo "✅ Database seeded successfully!\n";
        echo "📊 Created:\n";
        echo "   - " . count($pelanggan) . " customers\n";
        echo "   - " . count($produk) . " products\n";
        echo "   - " . ($id_transaksi - 1) . " transactions\n";
        echo "   - Stock records initialized\n";
    }
}