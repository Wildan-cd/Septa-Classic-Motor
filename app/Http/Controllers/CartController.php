<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{


    public function index()
    {
 
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();

 
        $cartItems = Cart::with('produk') 
                        ->where('user_id', $userId)
                        ->get();

        return view('cart', compact('cartItems')); 
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu untuk berbelanja.',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $idProduk = $request->id_produk; 
        $quantity = $request->quantity ?? 1;
        $userId   = Auth::id();

        $product = Produk::where('id_produk', $idProduk)->firstOrFail();

        if ($product->stok < 1) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Maaf, stok produk ini sedang habis.'
                ], 400);
            }
            return back()->with('error', 'Stok habis');
        }

        if ($quantity > $product->stok) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Stok tidak cukup. Sisa stok: {$product->stok}"
                ], 400);
            }
            return back()->with('error', 'Stok tidak cukup');
        }

        $existing = Cart::where('id_produk', $idProduk)
                        ->where('user_id', $userId)
                        ->first();

        if ($existing) {
            $existing->quantity += $quantity;
            $existing->save();
        } else {
            Cart::create([
                'user_id'    => $userId,
                'id_produk'  => $idProduk,
                'quantity'   => $quantity,


                'price' => $product->harga ?? $product->Harga ?? $product->price ?? 0, 
            ]);
        }

        if ($request->wantsJson()) {
        return response()->json([
            'status'     => 'success',
            'message'    => 'Produk berhasil masuk keranjang!',
            'cart_count' => Cart::where('user_id', $userId)->sum('quantity')
        ]);

        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Update qty
    public function update(Request $request)
    {
        $userId = Auth::id();
        $cart = session()->get("cart_{$userId}");

        if(isset($cart[$request->id_produk])){
            $cart[$request->id_produk]['qty'] = $request->qty;
            session()->put("cart_{$userId}", $cart);
        }

        return response()->json(['success' => true]);
    }

    // Remove item
    public function remove(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())
                    ->where('id_produk', $request->id)
                    ->first();
        
        if ($cart) {
            $cart->delete();
            return redirect()->back()->with('success', 'Produk berhasil dihapus');
        }
        
        return redirect()->back()->with('error', 'Gagal: Produk tidak ditemukan di keranjang Anda');
    }

    // Clear cart (opsional)
    public function clear()
    {
        $userId = Auth::id();
        session()->forget("cart_{$userId}");
        
        return redirect()->back()->with('success', 'Cart dikosongkan.');
    }
}