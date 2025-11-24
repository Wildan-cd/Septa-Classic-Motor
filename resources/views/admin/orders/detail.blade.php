@extends('layouts.admin')

@section('title', 'Order Details')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order-detail.css') }}">
{{-- Gunakan file CSS yang Anda berikan, simpan dengan nama detail-order.css --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

@section('content')
<div class="detail-container">
    
    {{-- Breadcrumb (Opsional, bisa disesuaikan dengan layout admin Anda) --}}
    <div class="detail-header">
        <nav class="breadcrumb">
            <a href="{{ route('admin.orders.index') }}" class="breadcrumb-link">Order List</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Order Details</span>
        </nav>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <div class="detail-card">
        {{-- Order Info Header --}}
        <div class="order-info-header">
            <div class="order-id-section">
                <h1 class="order-title">Orders ID: #{{ str_pad($order->id_transaksi, 5, '0', STR_PAD_LEFT) }}</h1>
                
                {{-- Badge Status Dinamis --}}
                @php
                    $badgeClass = 'status-pending'; // Default (Kuning)
                    if($order->status_pembayaran == 'Lunas') $badgeClass = 'status-completed'; // Hijau
                    if($order->status_pembayaran == 'Cancelled') $badgeClass = 'status-cancelled'; // Merah
                @endphp
                <span class="status-badge-large {{ $badgeClass }}">
                    {{ $order->status_pembayaran }}
                </span>

                <span class="order-date">
                    <i class="far fa-calendar-alt"></i> {{ date('M d, Y', strtotime($order->tgl_transaksi)) }}
                </span>
            </div>

            {{-- Action Buttons (Update Status) --}}
            <form action="{{ route('admin.orders.update', $order->id_transaksi) }}" method="POST" class="action-buttons">
                @csrf
                <div class="status-form">
                    <select name="status_pembayaran" class="status-select">
                        <option value="" disabled>Change Status</option>
                        <option value="Unpaid" {{ $order->status_pembayaran == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Pending" {{ $order->status_pembayaran == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Lunas" {{ $order->status_pembayaran == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Cancelled" {{ $order->status_pembayaran == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    {{-- Tambahan Select Pengiriman --}}
                    <select name="status_pengiriman" class="status-select">
                        <option value="" disabled>Delivery Status</option>
                        <option value="Diproses" {{ optional($order->pengiriman)->status_pengiriman == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Dikirim" {{ optional($order->pengiriman)->status_pengiriman == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="Selesai" {{ optional($order->pengiriman)->status_pengiriman == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <button type="button" class="btn-print" onclick="window.print()" title="Print">
                    <i class="fas fa-print"></i>
                </button>
                
                <button type="submit" class="btn-save">Save</button>
            </form>
        </div>

        {{-- Info Cards (3 Kolom) --}}
        <div class="info-cards">
            {{-- 1. Customer Card --}}
            <div class="info-card">
                <div class="info-icon customer-icon">
                    <i class="far fa-user"></i>
                </div>
                <div class="info-content">
                    <h3 class="info-title">Customer</h3>
                    <p class="info-name">{{ optional($order->pelanggan)->name ?? 'Guest User' }}</p>
                    <p class="info-detail">Email: {{ optional($order->pelanggan)->email ?? '-' }}</p>
                    <p class="info-detail">Phone: {{ optional($order->pengiriman)->no_hp ?? '-' }}</p>
                    
                    <button class="btn-view-profile">View Profile</button>
                </div>
            </div>

            {{-- 2. Order Info Card --}}
            <div class="info-card">
                <div class="info-icon order-icon">
                    <i class="fas fa-shopping-bag" style="color: white;"></i>
                </div>
                <div class="info-content">
                    <h3 class="info-title">Order Info</h3>
                    <p class="info-detail">Shipping: {{ optional($order->pengiriman)->status_pengiriman ?? 'Pending' }}</p>
                    <p class="info-detail">Payment Method: {{ $order->metode_pembayaran ?? 'QRIS' }}</p>
                    <p class="info-detail">Status: {{ $order->status_pembayaran }}</p>
                    
                    <button class="btn-download-info">Download Info</button>
                </div>
            </div>

            {{-- 3. Deliver To Card --}}
            <div class="info-card">
                <div class="info-icon deliver-icon">
                    <i class="fas fa-map-marker-alt" style="color: white;"></i>
                </div>
                <div class="info-content">
                    <h3 class="info-title">Deliver to</h3>
                    <p class="info-detail">Address: {{ optional($order->pengiriman)->alamat_lengkap ?? '-' }}</p>
                    <p class="info-detail">{{ optional($order->pengiriman)->kota ?? '' }}</p>
                    
                    <button class="btn-view-map">View Maps</button>
                </div>
            </div>
        </div>

        {{-- Notes Section --}}
        <div class="notes-section">
            <h4 class="notes-title">Note</h4>
            <textarea class="notes-textarea" readonly>{{ $order->catatan ?? 'Tidak ada catatan.' }}</textarea>
        </div>

        {{-- Products List --}}
        <div class="products-list">
            @foreach($order->detailTransaksi as $detail)
            <div class="product-item">
                <div class="product-image-wrapper">
                    @if($detail->produk && $detail->produk->gambar)
                        <img src="{{ asset($detail->produk->gambar) }}" class="product-image">
                    @else
                        <div class="product-placeholder">📦</div>
                    @endif
                </div>
                <div class="product-info">
                    <h4 class="product-name">{{ $detail->produk->nama_produk ?? 'Produk dihapus' }}</h4>
                    <p class="product-qty">Qty: {{ $detail->jumlah }}</p>
                </div>
                <div class="product-price">
                    Rp. {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- Price Summary --}}
        <div class="price-summary">
            <div class="summary-row">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value">Rp. {{ number_format($order->total_harga - $order->ongkir, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Shipping</span>
                <span class="summary-value">Rp. {{ number_format($order->ongkir, 0, ',', '.') }}</span>
            </div>
            
            <div class="summary-row summary-total">
                <span class="summary-label-total">Total</span>
                <span class="summary-value-total">Rp. {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

    </div>
</div>
@endsection