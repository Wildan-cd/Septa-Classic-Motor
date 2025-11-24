<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->where('kategori', $request->category);
        }
        
        if ($request->filled('price')) {
            $priceRange = explode('-', $request->price);
            if (count($priceRange) == 2) {
                $query->whereBetween('harga', [(int)$priceRange[0], (int)$priceRange[1]]);
            }
        }

        if ($request->filled('in_stock') && $request->in_stock == '1') {
            $query->where('stok', '>', 0);
        }

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('nama_produk', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('nama_produk', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('id_produk', 'desc');
                break;
        }
        
        $categories = Produk::select('kategori')
            ->whereNotNull('kategori') 
            ->distinct()
            ->pluck('kategori');

        $products = $query->paginate(12)->withQueryString();
        
        return view('catalog', compact('products', 'categories'));
    }
    
    public function show($id)
    {
        $product = Produk::findOrFail($id);

        $relatedProducts = Produk::where('kategori', $product->kategori)
            ->where('id_produk', '!=', $product->id_produk) // Ganti id_produk sesuai primary key model
            ->where('stok', '>', 0)
            ->limit(4)
            ->get();
        
        return view('product-detail', compact('product', 'relatedProducts'));
    }
}