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
            </div>
            <div class="bestsellers-list">
                @foreach($bestSellers as $product)
                <div class="bestseller-item">
                    <div class="bestseller-image">
                        {{-- Hapus onerror agar kita bisa lihat URL aslinya --}}
                        <img src="{{ asset($product->gambar) }}" 
                            alt="{{ $product->nama_produk }}"
                            style="width: 100%; height: 100%; object-fit: cover;">
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
        </div>
    </div>
    
    {{-- Recent Orders --}}
<div class="recent-orders-section">
    <div class="section-header">
        <h2 class="section-title">Recent Orders</h2>
        
        {{-- CUSTOM DROPDOWN MENU --}}
        <div class="custom-dropdown" style="position: relative;">
            <button class="stat-menu" onclick="toggleDropdown(event)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="1"></circle>
                    <circle cx="12" cy="5" r="1"></circle>
                    <circle cx="12" cy="19" r="1"></circle>
                </svg>
            </button>
            
            {{-- Isi Menu Dropdown --}}
            <div id="dropdown-content" class="custom-dropdown-content">
                <a href="javascript:void(0)" onclick="deleteSelectedOrders()" class="text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px; vertical-align: middle;"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Delete
                </a>
            </div>
        </div>
    </div>
    
    <div class="table-container">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="checkAll">
                    </th>
                    <th>Product</th>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr id="row-{{ $order->id_transaksi }}">
                    <td>
                        {{-- Value ID Transaksi untuk JS --}}
                        <input type="checkbox" class="order-checkbox" value="{{ $order->id_transaksi }}">
                    </td>
                    
                    {{-- Nama Produk --}}
                    <td>
                        @if($order->detailTransaksi->first())
                            <strong>{{ $order->detailTransaksi->first()->produk->nama_produk ?? 'Item Dihapus' }}</strong>
                            @if($order->detailTransaksi->count() > 1)
                                <small style="display:block; color:#888; font-size: 12px;">+{{ $order->detailTransaksi->count() - 1 }} lainnya</small>
                            @endif
                        @else
                            <span>-</span>
                        @endif
                    </td>

                    {{-- Order ID --}}
                    <td>#{{ str_pad($order->id_transaksi, 1, '0', STR_PAD_LEFT) }}</td>
                    
                    {{-- Tanggal --}}
                    <td>{{ date('M d, Y', strtotime($order->tgl_transaksi)) }}</td>
                    
                    {{-- Customer Name (Sesuai CSS .customer-info Anda) --}}
                    <td>
                        <div class="customer-info">
                            <div class="customer-avatar">
                                {{ substr(optional($order->pelanggan)->name ?? 'U', 0, 1) }}
                            </div>
                            <span>{{ optional($order->pelanggan)->name ?? 'Guest / Unknown' }}</span>
                        </div>
                    </td>

                    {{-- Status Badge (Sesuai CSS .status-badge Anda) --}}
                    <td>
                        @php
                            $status = optional($order->pengiriman)->status_pengiriman ?? 'Diproses';
                            // Mapping status ke class CSS Anda (lowercase)
                            // Contoh: 'Dikirim' -> 'status-dikirim'
                            $statusClass = 'status-' . strtolower($status);
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </td>

                    {{-- Amount --}}
                    <td>Rp. {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #999;">Belum ada pesanan terbaru.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    
// 1. Fitur Select All
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // 2. Fitur Delete Selected
function toggleDropdown(event) {
        event.stopPropagation(); // Cegah event bubbling
        const dropdown = document.getElementById("dropdown-content");
        dropdown.classList.toggle("show");
    }

    // Tutup dropdown jika klik di luar
    window.onclick = function(event) {
        if (!event.target.matches('.stat-menu') && !event.target.closest('.stat-menu')) {
            var dropdowns = document.getElementsByClassName("custom-dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    // --- 2. FUNGSI SELECT ALL ---
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // --- 3. FUNGSI DELETE SELECTED ---
    function deleteSelectedOrders() {
        // Sembunyikan dropdown setelah klik
        document.getElementById("dropdown-content").classList.remove("show");

        const selectedIds = Array.from(document.querySelectorAll('.order-checkbox:checked')).map(cb => cb.value);

        if (selectedIds.length === 0) {
            Swal.fire('Peringatan', 'Pilih pesanan terlebih dahulu.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Hapus Pesanan?',
            text: "Data yang dihapus tidak bisa kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Warna Merah sesuai CSS admin-red
            cancelButtonColor: '#8b8b8b',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('admin.orders.bulk_delete') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Berhasil!', data.message, 'success');
                        selectedIds.forEach(id => {
                            const row = document.getElementById('row-' + id);
                            if(row) row.remove();
                        });
                        document.getElementById('checkAll').checked = false;
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error', 'Gagal koneksi server.', 'error');
                });
            }
        });
    }
</script>
@endpush