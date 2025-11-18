<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Cart;
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
    $productId = $request->product_id;
    $quantity  = $request->quantity ?? 1;

    // CARI PRODUK
    $product = Produk::findOrFail($productId);

    // USER LOGIN ATAU GUEST?
    $userId     = auth::check() ? auth::id() : null;
    $sessionId  = session()->getId();

    // CARI APAKAH SUDAH ADA DI CART
    $existing = cart::where('product_id', $productId)
                    ->when($userId, fn($q) => $q->where('user_id', $userId))
                    ->when(!$userId, fn($q) => $q->where('session_id', $sessionId))
                    ->first();

    if ($existing) {
        // UPDATE QUANTITY
        $existing->update([
            'quantity' => $existing->quantity + $quantity
        ]);
    } else {
        // INSERT BARU
        Cart::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product->price,
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