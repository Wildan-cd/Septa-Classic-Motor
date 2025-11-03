<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total order
        $totalOrders = Transaksi::count();

        // Total completed
        $completedOrders = Transaksi::where('status_pembayaran', 'Lunas')->count();

        // Active (pending)
        $activeOrders = Transaksi::where('status_pembayaran', 'Pending')->count();

        // Penjualan per bulan (untuk grafik)
        $salesData = Transaksi::selectRaw('MONTH(tgl_transaksi) as bulan, SUM(total_harga) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        // Format untuk grafik
        $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $dataPoints = [];
        for ($i=1; $i<=12; $i++) {
            $dataPoints[] = $salesData[$i] ?? 0;
        }

        return view('admin.dashboard', [
            'active' => 'dashboard',
            'totalOrders' => $totalOrders,
            'completedOrders' => $completedOrders,
            'activeOrders' => $activeOrders,
            'labels' => json_encode($labels),
            'dataPoints' => json_encode($dataPoints),
        ]);
    }
}
