@extends('layouts.admin')

@section('title', 'Order List')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@section('content')
<div class="orders-container">
    <div class="orders-header">
        <h2 class="orders-title">Order List</h2>
        
        <!-- Date Filter -->
        <div class="date-filter">
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
                
                <!-- Status Filter -->
                <select name="status" class="status-filter">
                    <option value="">Change Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
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

    <!-- Recent Purchases Section -->
    <div class="purchases-section">
        <div class="section-header">
            <h3 class="section-title">Recent Purchases</h3>
            <button class="btn-more">⋮</button>
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
                    @forelse($transaksis as $transaksi)
                    <tr class="order-row" onclick="viewOrderDetail({{ $transaksi->id_transaksi }})">
                        <td class="checkbox-col">
                            <input type="checkbox" onclick="event.stopPropagation()">
                        </td>
                        <td>
                            <div class="product-names">
                                {{ $transaksi->detailTransaksi->pluck('produk.nama_produk')->take(2)->implode(', ') }}
                                @if($transaksi->detailTransaksi->count() > 2)
                                    <span class="more-items">+{{ $transaksi->detailTransaksi->count() - 2 }} more</span>
                                @endif
                            </div>
                        </td>
                        <td>#{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('M d, Y') }}</td>
                        <td>
                            <div class="customer-info">
                                <span class="customer-avatar">{{ substr($transaksi->pelanggan->nama_pelanggan, 0, 1) }}</span>
                                <span class="customer-name">{{ $transaksi->pelanggan->nama_pelanggan }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-{{ strtolower($transaksi->status_pembayaran) }}">
                                {{ $transaksi->status_pembayaran }}
                            </span>
                        </td>
                        <td class="amount">Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="empty-state">Belum ada pesanan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksis->hasPages())
        <div class="pagination-wrapper">
            {{ $transaksis->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/orders.js') }}"></script>
@endpush
@endsection