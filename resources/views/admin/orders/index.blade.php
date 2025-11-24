@extends('layouts.admin')

@section('title', 'Order List')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@section('content')
<div class="orders-container">
    <div class="orders-header">
        <h2 class="orders-title">Order List</h2>
        
        <div class="date-filter">
            {{-- Pastikan route ini benar --}}
            <form action="{{ route('admin.orders.index') }}" method="GET" class="filter-form">
                <div class="date-range">
                    <input type="date" 
                           name="start_date" 
                           value="{{ request('start_date') }}" 
                           class="date-input"
                           placeholder="Start Date">
                    <span class="date-separator">-</span>
                    <input type="date" 
                           name="end_date" 
                           value="{{ request('end_date') }}" 
                           class="date-input"
                           placeholder="End Date">
                </div>
                
                <select name="status" class="status-filter">
                    <option value="all">All Status</option>
                    <option value="Unpaid" {{ request('status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                
                <button type="submit" class="btn-filter">Filter</button>
                <a href="{{ route('admin.orders.index') }}" class="btn-reset">Reset</a>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
    @endif

    <div class="purchases-section">
        <div class="section-header">
            <h3 class="section-title">Recent Purchases</h3>
        </div>

        <div class="table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th class="checkbox-col">
                            <input type="checkbox" id="selectAll">
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
                    {{-- PERBAIKAN 1: Gunakan variabel $orders (dari controller) as $order --}}
                    @forelse($orders as $order) 
                    
                    {{-- PERBAIKAN 2: onclick mengarah ke route detail --}}
                    <tr class="order-row" onclick="window.location='{{ route('admin.orders.show', $order->id_transaksi) }}'" style="cursor: pointer;">
                        <td class="checkbox-col">
                            <input type="checkbox" onclick="event.stopPropagation()">
                        </td>
                        <td>
                            <div class="product-names">
                                {{-- Ambil nama produk --}}
                                @if($order->detailTransaksi->first())
                                    {{ $order->detailTransaksi->pluck('produk.nama_produk')->take(2)->implode(', ') }}
                                    @if($order->detailTransaksi->count() > 2)
                                        <span class="more-items">+{{ $order->detailTransaksi->count() - 2 }} more</span>
                                    @endif
                                @else
                                    <span class="text-danger">Item Terhapus</span>
                                @endif
                            </div>
                        </td>
                        {{-- PERBAIKAN 3: Ganti semua $transaksi menjadi $order --}}
                        <td>#{{ str_pad($order->id_transaksi, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->tgl_transaksi)->format('M d, Y') }}</td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-avatar">
                                    {{-- Gunakan Optional agar tidak error jika user dihapus --}}
                                    {{ substr(optional($order->pelanggan)->name ?? 'G', 0, 1) }}
                                </span>

                                <span class="customer-name">
                                    {{ optional($order->pelanggan)->name ?? 'Guest / User Terhapus' }}
                                </span>
                            </div>
                        </td>
                        <td>
                            {{-- Badge Status --}}
                            @php
                                $statusClass = strtolower($order->status_pembayaran);
                                if($statusClass == 'lunas') $statusClass = 'completed';
                                if($statusClass == 'unpaid') $statusClass = 'pending'; // css pending biasanya kuning
                            @endphp
                            <span class="status-badge status-{{ $statusClass }}">
                                {{ $order->status_pembayaran }}
                            </span>
                        </td>
                        <td class="amount">Rp. {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state" style="text-align: center; padding: 20px;">Belum ada pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PERBAIKAN 4: Gunakan $orders untuk pagination --}}
        @if($orders->hasPages())
        <div class="pagination-wrapper">
            {{ $orders->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
{{-- Hapus script js eksternal jika hanya berisi fungsi kosong, ganti dengan ini --}}
<script>
    // Script Select All Checkbox (Opsional)
    document.getElementById('selectAll').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('.checkbox-col input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });
</script>
@endpush
@endsection