<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        
        // if (empty($cart)) {
        //     return redirect()->route('cart.index')
        //         ->with('error', 'Keranjang belanja Anda kosong!');
        // }
        
        // Calculate totals
        $subtotal = 0;
        $cartItems = [];
        
        foreach ($cart as $id => $item) {
            $produk = Produk::find($id);
            if ($produk) {
                $itemTotal = $produk->harga * $item['quantity'];
                $subtotal += $itemTotal;
                
                $cartItems[] = [
                    'id' => $id,
                    'produk' => $produk,
                    'quantity' => $item['quantity'],
                    'subtotal' => $itemTotal
                ];
            }
        }
        
        $shipping = 0; 
        $total = $subtotal + $shipping;
        
        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }
    
    /**
     * Process checkout and redirect to confirm payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'company_name' => 'nullable|string|max:100',
            'street_address' => 'required|string|max:255',
            'apartment' => 'nullable|string|max:100',
            'town_city' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'email_address' => 'required|email|max:100'
        ]);
        
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang belanja Anda kosong!');
        }
        
        try {
            DB::beginTransaction();
            
            // Create or find pelanggan
            $pelanggan = Pelanggan::firstOrCreate(
                ['email' => $request->email_address],
                [
                    'nama_pelanggan' => $request->first_name . ($request->company_name ? ' - ' . $request->company_name : ''),
                    'no_telepon' => $request->phone_number,
                    'alamat' => $request->street_address . 
                               ($request->apartment ? ', ' . $request->apartment : '') . 
                               ', ' . $request->town_city
                ]
            );
            
            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $id => $item) {
                $produk = Produk::find($id);
                if ($produk) {
                    $subtotal += $produk->harga * $item['quantity'];
                }
            }
            
            $ongkir = 0;
            $total = $subtotal + $ongkir;
            
            // Create transaksi
            $transaksi = Transaksi::create([
                'id_pelanggan' => $pelanggan->id_pelanggan,
                'tanggal_transaksi' => now(),
                'total_harga' => $total,
                'status_pembayaran' => 'Pending',
                'metode_pembayaran' => 'QRIS',
                'ongkir' => $ongkir
            ]);
            
            // Create detail transaksi
            foreach ($cart as $id => $item) {
                $produk = Produk::find($id);
                if ($produk) {
                    // Check stock
                    if ($produk->stok < $item['quantity']) {
                        throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi!");
                    }
                    
                    DetailTransaksi::create([
                        'id_transaksi' => $transaksi->id_transaksi,
                        'id_produk' => $id,
                        'kuantitas' => $item['quantity'],
                        'harga_satuan' => $produk->harga
                    ]);
                    
                    // Update stock
                    $produk->stok -= $item['quantity'];
                    $produk->save();
                }
            }
            
            DB::commit();
            
            // Store transaction ID in session for confirm payment
            Session::put('pending_transaction_id', $transaksi->id_transaksi);
            
            return redirect()->route('confirm.payment')
                ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display confirm payment page
     */
    public function confirmPayment()
    {
        $transactionId = Session::get('pending_transaction_id');
        
        if (!$transactionId) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada transaksi yang perlu dikonfirmasi!');
        }
        
        $transaksi = Transaksi::with(['pelanggan', 'detailTransaksi.produk'])
            ->findOrFail($transactionId);
            
        return view('confirm-payment', compact('transaksi'));
    }
    
    /**
     * Confirm payment and redirect to WhatsApp
     */
    public function confirmPaymentSubmit(Request $request)
    {
        $transactionId = Session::get('pending_transaction_id');
        
        if (!$transactionId) {
            return redirect()->route('cart.index')
                ->with('error', 'Tidak ada transaksi yang perlu dikonfirmasi!');
        }
        
        $transaksi = Transaksi::with(['pelanggan', 'detailTransaksi.produk'])
            ->findOrFail($transactionId);
        
        // Generate WhatsApp message
        $message = "Halo Admin Septa Classic Motor,\n\n";
        $message .= "Saya ingin mengkonfirmasi pembayaran pesanan saya:\n\n";
        $message .= "Order ID: #" . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) . "\n";
        $message .= "Tanggal: " . $transaksi->tanggal_transaksi->format('d/m/Y') . "\n";
        $message .= "Nama: " . $transaksi->pelanggan->nama_pelanggan . "\n";
        $message .= "Total: Rp. " . number_format($transaksi->total_harga, 0, ',', '.') . "\n\n";
        $message .= "Detail Pesanan:\n";
        
        foreach ($transaksi->detailTransaksi as $index => $detail) {
            $message .= ($index + 1) . ". " . $detail->produk->nama_produk . " (x" . $detail->kuantitas . ") - Rp. " . number_format($detail->harga_satuan * $detail->kuantitas, 0, ',', '.') . "\n";
        }
        
        $message .= "\nSaya sudah melakukan pembayaran via QRIS.\n";
        $message .= "Mohon konfirmasi pesanan saya. Terima kasih!";
        
        // WhatsApp admin number (ganti dengan nomor admin sebenarnya)
        $adminPhone = '6282132785722'; // Format: 62xxxxx (tanpa +)
        
        // Clear cart and pending transaction
        Session::forget('cart');
        Session::forget('pending_transaction_id');
        
        // Redirect to WhatsApp
        $whatsappUrl = 'https://wa.me/' . $adminPhone . '?text=' . urlencode($message);
        
        return redirect()->away($whatsappUrl);
    }
}