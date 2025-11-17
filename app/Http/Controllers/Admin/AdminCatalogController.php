<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AdminCatalogController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $produks = Produk::orderBy('id_produk', 'desc')->get();
        
        // Hitung sales dari detail transaksi
        $produks->each(function($produk) {
            $produk->total_sales = $produk->detailTransaksi()->sum('jumlah');
            $produk->remaining = $produk->stok;
        });
        
        return view('admin.catalog.index', compact('produks'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('admin.catalog.form', ['produk' => null]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'nama_produk' => $request->nama_produk,
                'kategori' => $request->kategori,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'keterangan' => $request->keterangan
            ];

            // Handle image upload
            if ($request->hasFile('gambar')) {
                $image = $request->file('gambar');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $imageName);
                $data['gambar'] = 'uploads/products/' . $imageName;
            }

            Produk::create($data);

            return redirect()->route('admin.catalog.index')
                ->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.catalog.form', ['produk' => $produk]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $produk = Produk::findOrFail($id);
            
            $data = [
                'nama_produk' => $request->nama_produk,
                'kategori' => $request->kategori,
                'harga' => $request->harga,
                'stok' => $request->stok,
                'keterangan' => $request->keterangan
            ];

            // Handle image upload
            if ($request->hasFile('gambar')) {
                // Delete old image if exists
                if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                    unlink(public_path($produk->gambar));
                }

                $image = $request->file('gambar');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/products'), $imageName);
                $data['gambar'] = 'uploads/products/' . $imageName;
            }

            $produk->update($data);

            return redirect()->route('admin.catalog.index')
                ->with('success', 'Produk berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            
            // // Cek apakah produk sudah ada transaksi
            // if ($produk->detailTransaksi()->count() > 0) {
            //     return redirect()->back()
            //         ->with('error', 'Produk tidak dapat dihapus karena sudah ada transaksi!');
            // }

            // Delete image if exists
            if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                unlink(public_path($produk->gambar));
            }
            
            $produk->delete();
            
            return redirect()->route('admin.catalog.index')
                ->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get product data for AJAX request.
     */
    public function getData($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            $produk->total_sales = $produk->detailTransaksi()->sum('kuantitas');
            
            return response()->json([
                'success' => true,
                'data' => $produk
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }
}