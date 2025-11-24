<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'pengiriman', 'detailTransaksi.produk'])
                    ->orderBy('tgl_transaksi', 'desc');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tgl_transaksi', [$request->start_date, $request->end_date]);
        }

        if ($request->status && $request->status != 'all') {
            $query->where('status_pembayaran', $request->status);
        }

        $orders = $query->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Transaksi::with(['pelanggan', 'pengiriman', 'detailTransaksi.produk'])
                    ->findOrFail($id);

        return view('admin.orders.detail', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($request->status_pembayaran) {
            $transaksi->status_pembayaran = $request->status_pembayaran;
            $transaksi->save();
        }

        if ($request->status_pengiriman) {
            $pengiriman = Pengiriman::where('id_transaksi', $id)->first();
            if ($pengiriman) {
                $pengiriman->status_pengiriman = $request->status_pengiriman;
                $pengiriman->save();
            }
        }

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->detailTransaksi()->delete();
        $transaksi->pengiriman()->delete();
        $transaksi->delete();

        return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids; // Menerima array ID dari Javascript

        if ($ids && count($ids) > 0) {
            Transaksi::whereIn('id_transaksi', $ids)->delete();
            
            return response()->json(['status' => 'success', 'message' => 'Pesanan terpilih berhasil dihapus.']);
        }

        return response()->json(['status' => 'error', 'message' => 'Tidak ada pesanan yang dipilih.']);
    }
}