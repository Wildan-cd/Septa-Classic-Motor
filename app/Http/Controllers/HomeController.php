<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $topProducts = Produk::select(
                        'produk.*',
                        DB::raw('SUM(detail_transaksi.jumlah) as total_sales')
                    )
                    ->join('detail_transaksi', 'produk.id_produk', '=', 'detail_transaksi.id_produk')
                    ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
                    ->where('transaksi.status_pembayaran', 'Lunas')
                    ->groupBy(
                        'produk.id_produk', 
                        'produk.nama_produk', 
                        'produk.harga', 
                        'produk.gambar', 
                        'produk.stok', 
                        'produk.keterangan',
                        'produk.kategori',
                    )
                    ->orderByDesc('total_sales')
                    ->take(4) 
                    ->get();

                if ($topProducts->isEmpty()) {
                    $topProducts = Produk::latest()->take(4)->get();
                }

        $categories = [
            [
                'name' => 'Lampu Depan',
                'slug' => 'lampu-depan',
                'image' => 'images/categories/lampu-depan.jpg'
            ],
            [
                'name' => 'Spakbor',
                'slug' => 'spakbor',
                'image' => 'images/categories/spakbor.jpg'
            ],
            [
                'name' => 'Sein',
                'slug' => 'sein',
                'image' => 'images/categories/sein.jpg'
            ],
            [
                'name' => 'Other',
                'slug' => '',
                'image' => 'images/categories/other.jpg'
            ],
        ];

        return view('home', compact('topProducts', 'categories'));
    }
}