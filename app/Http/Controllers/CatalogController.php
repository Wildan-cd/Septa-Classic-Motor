<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }
        
        // Price filter
        if ($request->filled('price')) {
            $priceRange = explode('-', $request->price);
            if (count($priceRange) == 2) {
                $query->whereBetween('harga', [(int)$priceRange[0], (int)$priceRange[1]]);
            }
        }
        
        // Stock filter
        if ($request->filled('in_stock') && $request->in_stock == '1') {
            $query->where('stok', '>', 0);
        }
        
        // Sorting
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
        
        // Get unique categories for filter
        $categories = Produk::select('kategori')
            ->distinct()
            ->pluck('kategori')
            ->filter()
            ->values();
        
        // Paginate results
        $products = $query->paginate(12)->withQueryString();
        
        return view('catalog', compact('products', 'categories'));
    }
    
    public function show($id)
    {
        $product = Produk::findOrFail($id);
        
        // Get related products (same category)
        $relatedProducts = Produk::where('kategori', $product->kategori)
            ->where('id_produk', '!=', $product->id_produk)
            ->where('stok', '>', 0)
            ->limit(4)
            ->get();
        
        return view('product-detail', compact('product', 'relatedProducts'));
    }
}