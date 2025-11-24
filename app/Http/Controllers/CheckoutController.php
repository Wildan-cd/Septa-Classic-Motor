<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Pengiriman;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItems = Cart::with('produk')
                        ->where('user_id', Auth::id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $shipping = 20000; 

        $total = $subtotal + $shipping;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'street_address' => 'required',
            'town_city' => 'required',
            'phone_number' => 'required',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->get();
        if($cartItems->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $ongkir = 20000; 
        $total = $subtotal + $ongkir;

        try {
            DB::beginTransaction();

            foreach ($cartItems as $item) {
                $produkCek = Produk::find($item->id_produk);
                if ($produkCek->stok < $item->quantity) {
                    throw new \Exception("Stok untuk produk '{$produkCek->nama_produk}' tidak mencukupi. Sisa stok: {$produkCek->stok}");
                }
            }

            // A. SIMPAN TRANSAKSI
            $transaksi = Transaksi::create([
                        'id_pelanggan' => Auth::id(),
                        'tgl_transaksi' => Carbon::now(),
                        'total_harga' => $total,
                        'ongkir' => $ongkir,
                        'status_pembayaran' => 'Unpaid', 
                    ]);

            Pengiriman::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'nama_penerima' => $request->first_name . ' ' . $request->company_name,
                'alamat_lengkap' => $request->street_address,
                'kota' => $request->town_city,
                'no_hp' => $request->phone_number,
                'status_pengiriman' => 'Diproses'
            ]);

            foreach ($cartItems as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_produk' => $item->id_produk,
                    'jumlah' => $item->quantity, 
                    'harga_satuan' => $item->price
                ]);

                Produk::where('id_produk', $item->id_produk)
                    ->decrement('stok', $item->quantity);
            }

            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('confirm.payment', $transaksi->id_transaksi);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function confirmPayment($id)
    {
        $transaksi = Transaksi::with(['detailTransaksi.produk', 'pengiriman'])
                        ->where('id_transaksi', $id)
                        ->where('id_pelanggan', Auth::id()) 
                        ->firstOrFail();

        return view('confirm-payment', compact('transaksi'));
    }

    public function confirmPaymentSubmit(Request $request, $id)
    {
        $transaksi = Transaksi::where('id_transaksi', $id)
                        ->where('id_pelanggan', Auth::id())
                        ->firstOrFail();

        $transaksi->status_pembayaran = 'Pending'; 
        $transaksi->save();

        return redirect()->route('home')->with('success', 'Pembayaran dikonfirmasi! Menunggu verifikasi admin.');
    }

    public function cancelPayment($id)
    {
        $transaksi = Transaksi::where('id_transaksi', $id)
                        ->where('id_pelanggan', Auth::id())
                        ->firstOrFail();

        $transaksi->status_pembayaran = 'Cancelled';
        $transaksi->save();


        return redirect()->route('order.status')->with('info', 'Pesanan dibatalkan.');
    }
}   