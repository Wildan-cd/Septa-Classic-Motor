<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;

class OrderController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $orders = Transaksi::with(['detailTransaksi.produk', 'pengiriman'])
                    ->where('id_pelanggan', $userId)
                    ->orderBy('tgl_transaksi', 'desc')
                    ->get();

        $counts = [
            'all' => $orders->count(),
            'awaiting' => $orders->where('status_pembayaran', 'Unpaid')->count(),
            'pending' => $orders->where('status_pembayaran', 'Pending')->count(),
            'ondelivery' => $orders->filter(function($o) {
                return $o->status_pembayaran == 'On Delivery' || optional($o->pengiriman)->status_pengiriman == 'Dikirim';
            })->count(),
            'completed' => $orders->where('status_pembayaran', 'Completed')->count(),
            'canceled' => $orders->where('status_pembayaran', 'Cancelled')->count(),
        ];

        return view('order-status', compact('orders', 'counts'));
    }
}