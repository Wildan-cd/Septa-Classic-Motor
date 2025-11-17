<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    // Show cart
    public function index()
    {
        $userId = Auth::id();
        $cart = session()->get("cart_{$userId}", []);
        
        return view('cart', compact('cart'));
    }

    // Add to cart
    public function add(Request $request)
    {
        // Cek login
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan login terlebih dahulu!'
            ], 401);
        }

        try {
            // Validasi request
            $validated = $request->validate([
                'id_produk' => 'required|integer',
                'qty' => 'nullable|integer|min:1'
            ]);

            $productId = $validated['id_produk'];
            $qty = $validated['qty'] ?? 1;

            // Cari produk
            $product = Produk::find($productId);
            
            if (!$product) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk tidak ditemukan!'
                ], 404);
            }

            // Ambil cart berdasarkan user yang login
            $userId = Auth::id();
            $cart = session()->get("cart_{$userId}", []);

            if (isset($cart[$productId])) {
                $cart[$productId]['qty'] += $qty;
            } else {
                $cart[$productId] = [
                    'id_produk' => $product->id_produk,
                    'nama'      => $product->nama_produk,
                    'harga'     => $product->harga,
                    'qty'       => $qty,
                    'gambar'    => $product->gambar ?? null,
                ];
            }

            session()->put("cart_{$userId}", $cart);

            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => count($cart)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validasi gagal!',
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
        $userId = Auth::id();
        $cart = session()->get("cart_{$userId}");

        if(isset($cart[$request->id])){
            unset($cart[$request->id]);
            session()->put("cart_{$userId}", $cart);
        }

        return redirect()->back()->with('success', 'Item dihapus.');
    }

    // Clear cart (opsional)
    public function clear()
    {
        $userId = Auth::id();
        session()->forget("cart_{$userId}");
        
        return redirect()->back()->with('success', 'Cart dikosongkan.');
    }
}