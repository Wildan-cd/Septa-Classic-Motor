@extends('layouts.app')

@section('title', 'Confirm Payment')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/confirm-payment.css') }}">
@endpush

@section('content')

@php
    $noHpAdmin = '6282234322320';
    
    $pesan = "Halo Admin, saya sudah melakukan pembayaran via QRIS.\n\n";
    $pesan .= "ID Transaksi: #SCM" . str_pad($transaksi->id_transaksi, 5, '0', STR_PAD_LEFT) . "\n";
    $pesan .= "Nama: " . Auth::user()->name . "\n";
    $pesan .= "Total: Rp " . number_format($transaksi->total_harga, 0, ',', '.') . "\n";
    $pesan .= "Mohon diproses ya.";
    
    $linkWA = "https://wa.me/" . $noHpAdmin . "?text=" . urlencode($pesan);
@endphp

<div class="confirm-container">
    <div class="breadcrumb">
        <button onclick="history.back()" class="back-button">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
        <span class="breadcrumb-separator">></span>
        <a href="{{ route('catalog') }}" class="breadcrumb-link">Catalog</a>
        <span class="breadcrumb-separator">></span>
        <a href="{{ route('cart.index') }}" class="breadcrumb-link">Cart</a>
        <span class="breadcrumb-separator">></span>
        <span class="breadcrumb-current">Confirm Payment</span>
    </div>

    <h1 class="confirm-title">Confirm Payment</h1>

    <div class="confirm-content">
        <div class="order-items-section">
            @foreach($transaksi->detailTransaksi as $detail)
            <div class="order-item">
                <div class="item-image-wrapper">
                    @if($detail->produk->gambar && file_exists(public_path($detail->produk->gambar)))
                        {{-- Perbaikan path gambar (biasanya ada folder uploads/products) --}}
                        <img src="{{ asset($detail->produk->gambar) }}" 
                             alt="{{ $detail->produk->nama_produk }}"
                             class="item-image">
                    @else
                        <div class="item-placeholder">📦</div>
                    @endif
                </div>
                <div class="item-info">
                    <h3 class="item-name">{{ $detail->produk->nama_produk }}</h3>
                    <p class="item-notes">Qty: {{ $detail->jumlah }}</p>
                </div>
                <div class="item-price">
                    Rp. {{ number_format($detail->harga_satuan * $detail->jumlah, 0, ',', '.') }}
                </div>
            </div>
            @endforeach

            <div class="price-summary">
                <div class="price-row">
                    <span class="price-label">Subtotal</span>
                    {{-- Rumus subtotal dikurangi ongkir --}}
                    <span class="price-value">Rp. {{ number_format($transaksi->total_harga - $transaksi->ongkir, 0, ',', '.') }}</span>
                </div>
                <div class="price-row">
                    <span class="price-label">Shipping</span>
                    <span class="price-value">Rp. {{ number_format($transaksi->ongkir, 0, ',', '.') }}</span>
                </div>
                <div class="price-row" style="border-top: 1px solid #eee; margin-top: 10px; padding-top: 10px;">
                    <span class="price-label" style="font-weight: bold;">Total</span>
                    <span class="price-value" style="font-weight: bold;">Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="qris-section">
                <h3 class="qris-title">Scan QRIS untuk Pembayaran</h3>
                <div class="qris-wrapper">
                    <img src="{{ asset('images/qris SCM.png') }}" 
                         alt="QRIS Code" 
                         class="qris-image">
                </div>
                <p class="qris-info">Scan kode QR di atas menggunakan aplikasi pembayaran digital Anda</p>
                <p class="qris-amount">Total Pembayaran: <strong>Rp. {{ number_format($transaksi->total_harga, 0, ',', '.') }}</strong></p>
            </div>

            <form action="{{ route('confirm.payment.submit', $transaksi->id_transaksi) }}" method="POST" id="paymentForm">
                @csrf
                <button type="submit" class="btn-confirm">
                    Confirm Payment
                </button>
            </form>

            <form action="{{ route('payment.cancel', $transaksi->id_transaksi) }}" method="POST" id="cancelForm" style="display:none;">
                @csrf
            </form>
        </div>
    </div>
</div>

@push('scripts')
{{-- SweetAlert CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 2. KIRIM VARIABEL PHP KE JAVASCRIPT EKSTERNAL
    // Ini penting agar file confirm-payment.js bisa membaca link WA
    const waLink = "{!! $linkWA !!}";
</script>

{{-- Load Script Eksternal Anda --}}
<script src="{{ asset('js/confirm-payment.js') }}"></script>
@endpush
@endsection