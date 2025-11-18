@extends('layouts.app')

@section('title', 'Confirm Payment')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/confirm-payment.css') }}">
@endpush

@section('content')
<div class="confirm-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <button onclick="history.back()" class="back-button">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
        <span class="breadcrumb-separator">></span>
        <a href="{{ route('shop') }}" class="breadcrumb-link">Shop</a>
        <span class="breadcrumb-separator">></span>
        <a href="{{ route('cart.index') }}" class="breadcrumb-link">Cart</a>
        <span class="breadcrumb-separator">></span>
        <span class="breadcrumb-current">Confirm Payment</span>
    </div>

    <h1 class="confirm-title">Confirm Payment</h1>

    <div class="confirm-content">
        <!-- Order Items -->
        <div class="order-items-section">
            @foreach($transaksi->detailTransaksi as $detail)
            <div class="order-item">
                <div class="item-image-wrapper">
                    @if($detail->produk->gambar && file_exists(public_path($detail->produk->gambar)))
                        <img src="{{ asset($detail->produk->gambar) }}" 
                             alt="{{ $detail->produk->nama_produk }}"
                             class="item-image">
                    @else
                        <div class="item-placeholder">📦</div>
                    @endif
                </div>
                <div class="item-info">
                    <h3 class="item-name">{{ $detail->produk->nama_produk }}</h3>
                    <p class="item-notes">Notes</p>
                </div>
                <div class="item-price">
                    Rp. {{ number_format($detail->harga_satuan * $detail->kuantitas, 0, ',', '.') }}
                </div>
            </div>
            @endforeach

            <!-- Price Summary -->
            <div class="price-summary">
                <div class="price-row">
                    <span class="price-label">Total</span>
                    <span class="price-value">Rp. {{ number_format($transaksi->detailTransaksi->sum(function($d) { return $d->harga_satuan * $d->kuantitas; }), 0, ',', '.') }}</span>
                </div>
                <div class="price-row">
                    <span class="price-label">Subtotal</span>
                    <span class="price-value">Rp. {{ number_format($transaksi->detailTransaksi->sum(function($d) { return $d->harga_satuan * $d->kuantitas; }), 0, ',', '.') }}</span>
                </div>
                <div class="price-row">
                    <span class="price-label">Shipping</span>
                    <span class="price-value">Rp. {{ number_format($transaksi->ongkir, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- QRIS Section -->
            <div class="qris-section">
                <h3 class="qris-title">Scan QRIS untuk Pembayaran</h3>
                <div class="qris-wrapper">
                    <img src="{{ asset('images/qris-code.png') }}" 
                         alt="QRIS Code" 
                         class="qris-image">
                </div>
                <p class="qris-info">Scan kode QR di atas menggunakan aplikasi pembayaran digital Anda</p>
                <p class="qris-amount">Total Pembayaran: <strong>Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></p>
            </div>

            <!-- Confirm Button -->
            <form action="{{ route('confirm.payment.submit') }}" method="POST">
                @csrf
                <button type="submit" class="btn-confirm">
                    Confirm Payment
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/confirm-payment.js') }}"></script>
@endpush
@endsection