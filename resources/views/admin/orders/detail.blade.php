@extends('layouts.admin')

@section('title', 'Order Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order-detail.css') }}">
@endpush

@section('content')
<div class="detail-container">
    <!-- Header -->
    <div class="detail-header">
        <div class="breadcrumb">
            <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">Order List</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Order Details</span>
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

    <div class="detail-card">
        <!-- Order Info Header -->
        <div class="order-info-header">
            <div class="order-id-section">
                <h2 class="order-title">Orders ID: #{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</h2>
                <span class="status-badge-large status-{{ strtolower($transaksi->status_pembayaran) }}">
                    {{ $transaksi->status_pembayaran }}
                </span>
                <div class="order-date">
                    📅 {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('M d, Y') }}
                </div>
            </div>

            <div class="action-buttons">
                <!-- Status Change Form -->
                <form action="{{ route('admin.orders.updateStatus', $transaksi->id_transaksi) }}" 
                      method="POST" 
                      class="status-form"
                      onsubmit="return confirm('Are you sure you want to change the order status?')">
                    @csrf
                    @method('PUT')
                    <select name="status_pembayaran" class="status-select" required>
                        <option value="">Change Status</option>
                        <option value="Pending" {{ $transaksi->status_pembayaran == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Completed" {{ $transaksi->status_pembayaran == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="Cancelled" {{ $transaksi->status_pembayaran == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="btn-update-status">Update</button>
                </form>

                <a href="{{ route('admin.orders.print', $transaksi->id_transaksi) }}" 
                   target="_blank" 
                   class="btn-print">
                    🖨️
                </a>
                
                <button class="btn-save">Save</button>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="info-cards">
            <!-- Customer Info -->
            <div class="info-card">
                <div class="info-icon customer-icon">👤</div>
                <div class="info-content">
                    <h4 class="info-title">Customer</h4>
                    <p class="info-name">{{ $transaksi->pelanggan->nama_pelanggan }}</p>
                    <p class="info-detail">Email: {{ $transaksi->pelanggan->email }}</p>
                    <p class="info-detail">Phone: {{ $transaksi->pelanggan->no_telepon }}</p>
                    <button class="btn-view-profile">View profile</button>
                </div>
            </div>

            <!-- Order Info -->
            <div class="info-card">
                <div class="info-icon order-icon">📦</div>
                <div class="info-content">
                    <h4 class="info-title">Order Info</h4>
                    <p class="info-detail">Payment: {{ $transaksi->metode_pembayaran ?? 'Cash' }}</p>
                    <p class="info-detail">Order ID: #{{ str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) }}</p>
                    <p class="info-detail">Status: {{ $transaksi->status_pembayaran }}</p>
                    <button class="btn-download-info">Download Info</button>
                </div>
            </div>

            <!-- Deliver To -->
            <div class="info-card">
                <div class="info-icon deliver-icon">🚚</div>
                <div class="info-content">
                    <h4 class="info-title">Deliver to</h4>
                    <p class="info-detail">{{ $transaksi->pelanggan->alamat }}</p>
                    <button class="btn-view-map">View map</button>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="notes-section">
            <h4 class="notes-title">Note</h4>
            <textarea class="notes-textarea" 
                      placeholder="Type some notes"
                      readonly>{{ $transaksi->catatan ?? 'No notes' }}</textarea>
        </div>

        <!-- Products List -->
        <div class="products-list">
            @foreach($transaksi->detailTransaksi as $detail)
            <div class="product-item">
                <div class="product-image-wrapper">
                    @if($detail->produk->gambar && file_exists(public_path($detail->produk->gambar)))
                        <img src="{{ asset($detail->produk->gambar) }}" 
                             alt="{{ $detail->produk->nama_produk }}"
                             class="product-image">
                    @else
                        <div class="product-placeholder">📦</div>
                    @endif
                </div>
                <div class="product-info">
                    <h5 class="product-name">{{ $detail->produk->nama_produk }}</h5>
                    <p class="product-qty">Qty: {{ $detail->kuantitas }}</p>
                </div>
                <div class="product-price">
                    Rp. {{ number_format($detail->harga_satuan * $detail->kuantitas, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Price Summary -->
        <div class="price-summary">
            <div class="summary-row">
                <span class="summary-label">Total</span>
                <span class="summary-value">Rp. {{ number_format($transaksi->detailTransaksi->sum(function($d) { return $d->harga_satuan * $d->kuantitas; }), 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value">Rp. {{ number_format($transaksi->detailTransaksi->sum(function($d) { return $d->harga_satuan * $d->kuantitas; }), 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Shipping</span>
                <span class="summary-value">Rp. {{ number_format($transaksi->ongkir ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row summary-total">
                <span class="summary-label-total">Grand Total</span>
                <span class="summary-value-total">Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/order-detail.js') }}"></script>
@endpush
@endsection