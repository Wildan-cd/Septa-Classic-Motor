<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\DetailTransaksi;
use App\Models\Pengiriman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get current month and previous month for comparison
        $currentMonth = Carbon::now()->month;
        $previousMonth = Carbon::now()->subMonth()->month;
        $currentYear = Carbon::now()->year;

        // Calculate Total Orders - Semua transaksi bulan ini
        $totalOrdersCurrentMonth = Transaksi::whereMonth('tgl_transaksi', $currentMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->sum('total_harga');

        $totalOrdersPreviousMonth = Transaksi::whereMonth('tgl_transaksi', $previousMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->sum('total_harga');

        $totalOrdersChange = $totalOrdersPreviousMonth > 0 
            ? (($totalOrdersCurrentMonth - $totalOrdersPreviousMonth) / $totalOrdersPreviousMonth) * 100 
            : 0;

        // Calculate Active Orders - Transaksi dengan status Pending atau yang sedang diproses/dikirim
        $activeOrdersCurrentMonth = Transaksi::whereMonth('tgl_transaksi', $currentMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->where(function($query) {
                $query->where('status_pembayaran', 'Pending')
                      ->orWhereHas('pengiriman', function($q) {
                          $q->whereIn('status_pengiriman', ['Diproses', 'Dikirim']);
                      });
            })
            ->sum('total_harga');

        $activeOrdersPreviousMonth = Transaksi::whereMonth('tgl_transaksi', $previousMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->where(function($query) {
                $query->where('status_pembayaran', 'Pending')
                      ->orWhereHas('pengiriman', function($q) {
                          $q->whereIn('status_pengiriman', ['Diproses', 'Dikirim']);
                      });
            })
            ->sum('total_harga');

        $activeOrdersChange = $activeOrdersPreviousMonth > 0 
            ? (($activeOrdersCurrentMonth - $activeOrdersPreviousMonth) / $activeOrdersPreviousMonth) * 100 
            : 0;

        // Calculate Completed Orders - Transaksi Lunas dengan pengiriman Selesai
        $completedOrdersCurrentMonth = Transaksi::whereMonth('tgl_transaksi', $currentMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->where('status_pembayaran', 'Lunas')
            ->whereHas('pengiriman', function($q) {
                $q->where('status_pengiriman', 'Selesai');
            })
            ->sum('total_harga');

        $completedOrdersPreviousMonth = Transaksi::whereMonth('tgl_transaksi', $previousMonth)
            ->whereYear('tgl_transaksi', $currentYear)
            ->where('status_pembayaran', 'Lunas')
            ->whereHas('pengiriman', function($q) {
                $q->where('status_pengiriman', 'Selesai');
            })
            ->sum('total_harga');

        $completedOrdersChange = $completedOrdersPreviousMonth > 0 
            ? (($completedOrdersCurrentMonth - $completedOrdersPreviousMonth) / $completedOrdersPreviousMonth) * 100 
            : 0;

        $stats = [
            'total_orders' => $totalOrdersCurrentMonth,
            'total_orders_change' => round($totalOrdersChange, 1),
            'active_orders' => $activeOrdersCurrentMonth,
            'active_orders_change' => round($activeOrdersChange, 1),
            'completed_orders' => $completedOrdersCurrentMonth,
            'completed_orders_change' => round($completedOrdersChange, 1),
        ];

        // Get Best Sellers - Produk dengan total penjualan tertinggi
        $bestSellers = Produk::select(
                'produk.id_produk',
                'produk.nama_produk',
                'produk.harga',
                DB::raw('SUM(detail_transaksi.jumlah) as total_sales'),
                DB::raw('SUM(detail_transaksi.jumlah * detail_transaksi.harga_satuan) as total_revenue')
            )
            ->join('detail_transaksi', 'produk.id_produk', '=', 'detail_transaksi.id_produk')
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->where('transaksi.status_pembayaran', 'Lunas')
            ->groupBy('produk.id_produk', 'produk.nama_produk', 'produk.harga')
            ->orderByDesc('total_sales')
            ->limit(3)
            ->get();

        // Get Sales Chart Data (Monthly by default)
        $salesChartData = $this->getSalesChartData('monthly');

        // Get Recent Orders with all relations
        $recentOrders = Transaksi::with(['pelanggan', 'pengiriman', 'detailTransaksi.produk'])
            ->orderBy('tgl_transaksi', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($order) {
                // Get product names from detail transaksi
                $productNames = $order->detailTransaksi->pluck('produk.nama_produk')->filter()->implode(', ');
                $order->product_name = $productNames ?: 'Lorem Ipsum';
                return $order;
            });

        return view('admin.dashboard', compact('stats', 'bestSellers', 'salesChartData', 'recentOrders'));
    }

    public function getSalesData(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $data = $this->getSalesChartData($period);
        
        return response()->json($data);
    }

    private function getSalesChartData($period = 'monthly')
    {
        $labels = [];
        $data = [];
        
        if ($period === 'weekly') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('D');
                
                $sales = Transaksi::whereDate('tgl_transaksi', $date->format('Y-m-d'))
                    ->sum('total_harga');
                    
                $data[] = (float) $sales;
            }
        } elseif ($period === 'monthly') {
            // Last 6 months
            $months = ['JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $months[5 - $i];
                
                $sales = Transaksi::whereMonth('tgl_transaksi', $date->month)
                    ->whereYear('tgl_transaksi', $date->year)
                    ->sum('total_harga');
                    
                $data[] = (float) $sales;
            }
        } else { // yearly
            // Last 5 years
            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i)->year;
                $labels[] = (string) $year;
                
                $sales = Transaksi::whereYear('tgl_transaksi', $year)
                    ->sum('total_harga');
                    
                $data[] = (float) $sales;
            }
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}