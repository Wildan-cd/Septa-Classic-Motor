@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <h1 class="dashboard-title">Dashboard</h1>
    
    {{-- Stats Cards --}}
    <div class="stats-grid">
        {{-- Total Orders --}}
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total Orders</span>
                <button class="stat-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="12" cy="5" r="1"></circle>
                        <circle cx="12" cy="19" r="1"></circle>
                    </svg>
                </button>
            </div>
            <div class="stat-value">Rp. {{ number_format($stats['total_orders'], 0, ',', '.') }}</div>
            <div class="stat-footer">
                <span class="stat-change {{ $stats['total_orders_change'] >= 0 ? 'positive' : 'negative' }}">
                    {{ $stats['total_orders_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['total_orders_change']) }}%
                </span>
                <span class="stat-compare">Compared to Nov 2025</span>
            </div>
        </div>
        
        {{-- Active Orders --}}
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Active Orders</span>
                <button class="stat-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="12" cy="5" r="1"></circle>
                        <circle cx="12" cy="19" r="1"></circle>
                    </svg>
                </button>
            </div>
            <div class="stat-value">Rp. {{ number_format($stats['active_orders'], 0, ',', '.') }}</div>
            <div class="stat-footer">
                <span class="stat-change {{ $stats['active_orders_change'] >= 0 ? 'positive' : 'negative' }}">
                    {{ $stats['active_orders_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['active_orders_change']) }}%
                </span>
                <span class="stat-compare">Compared to Nov 2025</span>
            </div>
        </div>
        
        {{-- Completed Orders --}}
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Completed Orders</span>
                <button class="stat-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="12" cy="5" r="1"></circle>
                        <circle cx="12" cy="19" r="1"></circle>
                    </svg>
                </button>
            </div>
            <div class="stat-value">Rp. {{ number_format($stats['completed_orders'], 0, ',', '.') }}</div>
            <div class="stat-footer">
                <span class="stat-change {{ $stats['completed_orders_change'] >= 0 ? 'positive' : 'negative' }}">
                    {{ $stats['completed_orders_change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['completed_orders_change']) }}%
                </span>
                <span class="stat-compare">Compared to Nov 2025</span>
            </div>
        </div>
    </div>
    
    {{-- Charts Section --}}
    <div class="charts-grid">
        {{-- Sale Graph --}}
        <div class="chart-card">
            <div class="chart-header">
                <h2 class="chart-title">Sale Graph</h2>
                <div class="chart-filters">
                    <button class="filter-btn" data-period="weekly">WEEKLY</button>
                    <button class="filter-btn active" data-period="monthly">MONTHLY</button>
                    <button class="filter-btn" data-period="yearly">YEARLY</button>
                </div>
            </div>
            <div class="chart-body">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
        
        {{-- Best Sellers --}}
        <div class="bestsellers-card">
            <div class="bestsellers-header">
                <h2 class="chart-title">Best Sellers</h2>
                <button class="stat-menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="1"></circle>
                        <circle cx="12" cy="5" r="1"></circle>
                        <circle cx="12" cy="19" r="1"></circle>
                    </svg>
                </button>
            </div>
            <div class="bestsellers-list">
                @foreach($bestSellers as $product)
                <div class="bestseller-item">
                    <div class="bestseller-image">
                        <img src="{{ asset($product->gambar ?? 'images/placeholder.jpg') }}" alt="{{ $product->nama_produk }}">
                    </div>
                    <div class="bestseller-info">
                        <div class="bestseller-name">{{ $product->nama_produk }}</div>
                        <div class="bestseller-price">Rp{{ number_format($product->harga, 2) }}</div>
                        <div class="bestseller-sales">{{ $product->total_sales }} sales</div>
                    </div>
                    <div class="bestseller-total">
                        Rp{{ number_format($product->harga, 2) }}
                    </div>
                </div>
                @endforeach
            </div>
            <button class="btn-report">REPORT</button>
        </div>
    </div>
    
    {{-- Recent Orders --}}
    <div class="recent-orders-section">
        <div class="section-header">
            <h2 class="section-title">Recent Orders</h2>
            <button class="stat-menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
                </svg>
            </button>
        </div>
        
        <div class="table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Product</th>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Customer Name</th>
                        <th>Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td><input type="checkbox" class="order-checkbox"></td>
                        <td>{{ $order->product_name ?? 'Lorem Ipsum' }}</td>
                        <td>#{{ $order->id_transaksi }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->tgl_transaksi)->format('M jS, Y') }}</td>
                        <td>
                            <div class="customer-info">
                                <div class="customer-avatar">{{ substr($order->pelanggan->nama ?? 'U', 0, 1) }}</div>
                                <span>{{ $order->pelanggan->nama ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ strtolower($order->pengiriman->status_pengiriman ?? 'pending') }}">
                                {{ $order->pengiriman->status_pengiriman ?? 'Pending' }}
                            </span>
                        </td>
                        <td>Rp. {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Sales Chart Data from Controller
    const salesData = @json($salesChartData);
    
    // Initialize Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    let salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: salesData.labels,
            datasets: [{
                label: 'Sales',
                data: salesData.data,
                borderColor: '#4169E1',
                backgroundColor: 'rgba(65, 105, 225, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#4169E1',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#000',
                    bodyColor: '#666',
                    borderColor: '#ddd',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp. ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f0f0f0'
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp. ' + (value / 1000).toFixed(0) + 'K';
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // Filter buttons functionality
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(btn => {
        btn.addEventListener('click', async function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const period = this.dataset.period;
            
            // Fetch new data based on period
            try {
                const response = await fetch(`/admin/dashboard/sales-data?period=${period}`);
                const data = await response.json();
                
                // Update chart
                salesChart.data.labels = data.labels;
                salesChart.data.datasets[0].data = data.data;
                salesChart.update();
            } catch (error) {
                console.error('Error fetching sales data:', error);
            }
        });
    });
    
    // Select All Checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        orderCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush