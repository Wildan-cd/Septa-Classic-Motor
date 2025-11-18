<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'detailTransaksi.produk'])
            ->orderBy('id_transaksi', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pembayaran', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }

        $transaksis = $query->paginate(15);
        
        return view('admin.orders.index', compact('transaksis'));
    }

    /**
     * Display the specified order details.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'detailTransaksi.produk'])
            ->findOrFail($id);
            
        return view('admin.orders.detail', compact('transaksi'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:Pending,Completed,Cancelled',
            'catatan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $transaksi = Transaksi::findOrFail($id);
            
            // Update status
            $transaksi->status_pembayaran = $request->status_pembayaran;
            
            // Jika ada catatan
            if ($request->catatan) {
                $transaksi->catatan = $request->catatan;
            }
            
            $transaksi->save();

            // Jika status diubah menjadi Cancelled, kembalikan stok
            if ($request->status_pembayaran == 'Cancelled') {
                foreach ($transaksi->detailTransaksi as $detail) {
                    $produk = $detail->produk;
                    $produk->stok += $detail->kuantitas;
                    $produk->save();
                }
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $id)
                ->with('success', 'Status pesanan berhasil diupdate!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Print invoice
     */
    public function print($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'detailTransaksi.produk'])
            ->findOrFail($id);
            
        return view('admin.orders.print', compact('transaksi'));
    }

    /**
     * Get order data for viewing details
     */
    public function viewDetails($id)
    {
        $transaksi = Transaksi::with(['pelanggan', 'detailTransaksi.produk'])
            ->findOrFail($id);
            
        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }
}