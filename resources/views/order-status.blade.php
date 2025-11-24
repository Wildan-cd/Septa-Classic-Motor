@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order-status.css') }}">
{{-- Tambahan Style untuk Status Baru agar warnanya beda --}}
<style>
    .badge.unpaid { background-color: #ffc107; color: #000; } /* Kuning */
    .badge.pending { background-color: #17a2b8; color: #fff; } /* Biru Muda */
    .badge.canceled { background-color: #dc3545; color: #fff; } /* Merah */
    .badge.ondeliver { background-color: #007bff; color: #fff; } /* Biru */
    .badge.completed { background-color: #28a745; color: #fff; } /* Hijau */
    
    /* Tombol Pay Now khusus Unpaid */
    .pay-btn {
        background-color: #000;
        color: #fff;
        padding: 5px 15px;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
    }
    .pay-btn:hover { background-color: #333; }
</style>
@endpush

@section('title', 'Order Status - Septa Classic Motor')

@section('content')

{{-- Breadcrumb --}}
<section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item active">Order Status</span>
        </nav>
    </div>
</section>

<body>

<div class="container">
    <h2>Order Status</h2>

    {{-- TABS NAVIGASI (Angka dinamis dari Controller) --}}
    <div class="tabs">
        <div class="tab active" onclick="filterOrders('all')">All Order ({{ $counts['all'] }})</div>
        <div class="tab" onclick="filterOrders('unpaid')">Awaiting Pay ({{ $counts['awaiting'] }})</div>
        <div class="tab" onclick="filterOrders('pending')">Pending ({{ $counts['pending'] }})</div>
        <div class="tab" onclick="filterOrders('ondelivery')">On Deliver ({{ $counts['ondelivery'] }})</div>
        <div class="tab" onclick="filterOrders('completed')">Completed ({{ $counts['completed'] }})</div>
        <div class="tab" onclick="filterOrders('canceled')">Canceled ({{ $counts['canceled'] }})</div>
    </div>

    {{-- JIKA TIDAK ADA PESANAN SAMA SEKALI --}}
    @if($orders->isEmpty())
        <div style="text-align: center; padding: 60px 0; color: #888;">
            <p style="font-size: 18px; margin-bottom: 10px;">Belum ada riwayat pesanan.</p>
            <a href="{{ route('catalog') }}" style="text-decoration: underline; color: #000; font-weight: bold;">Mulai Belanja</a>
        </div>
    @endif

    {{-- LOOPING KARTU PESANAN --}}
    @foreach($orders as $order)
        
        {{-- LOGIKA PENENTUAN STATUS & WARNA BADGE --}}
        @php
            $badgeClass = 'unpaid'; 
            $statusText = 'Unpaid';
            $dataCategory = 'unpaid';

            // 1. Cek Status Pembayaran
            if($order->status_pembayaran == 'Pending') {
                $badgeClass = 'pending';
                $statusText = 'Verifying'; // Menunggu konfirmasi admin
                $dataCategory = 'pending';
            } 
            elseif($order->status_pembayaran == 'Cancelled') {
                $badgeClass = 'canceled';
                $statusText = 'Cancelled';
                $dataCategory = 'canceled';
            }
            elseif($order->status_pembayaran == 'Completed') {
                $badgeClass = 'completed';
                $statusText = 'Completed';
                $dataCategory = 'completed';
            }

            // 2. Cek Status Pengiriman (Override jika sedang dikirim)
            // Jika status pembayaran 'On Delivery' ATAU di tabel pengiriman statusnya 'Dikirim'
            if($order->status_pembayaran == 'On Delivery' || optional($order->pengiriman)->status_pengiriman == 'Dikirim') {
                $badgeClass = 'ondeliver';
                $statusText = 'On Delivery';
                $dataCategory = 'ondelivery';
            }
        @endphp

        {{-- ITEM CARD (Attribute data-category untuk filter JS) --}}
        <div class="order-card" data-category="{{ $dataCategory }}">
            <div class="order-top">
                <div>
                    <strong>Order ID</strong><br>
                    {{-- Format ID Custom: SCM(Tahun)-(ID Transaksi) --}}
                    SCM{{ date('y', strtotime($order->tgl_transaksi)) }}-{{ str_pad($order->id_transaksi, 6, '0', STR_PAD_LEFT) }}
                    <br>
                    <small style="color: #666; font-size: 12px;">{{ date('d M Y', strtotime($order->tgl_transaksi)) }}</small>
                </div>
                
                {{-- Badge Status --}}
                <div class="badge {{ $badgeClass }}">
                    {{ $statusText }}
                </div>
            </div>

            {{-- LOOPING PRODUK DALAM ORDER INI --}}
            @foreach($order->detailTransaksi as $detail)
            <div class="product-box">
                {{-- Gambar --}}
                <div class="product-img">
                    @if($detail->produk && $detail->produk->gambar)
                        <img src="{{ asset($detail->produk->gambar) }}" 
                             alt="Produk" 
                             style="width:100%; height:100%; object-fit:cover; border-radius:4px;">
                    @else
                        <div style="width:100%; height:100%; background:#eee; display:flex; align-items:center; justify-content:center;">📦</div>
                    @endif
                </div>

                {{-- Info Produk --}}
                <div class="product-info">
                    <strong>{{ $detail->produk->nama_produk ?? 'Produk tidak tersedia' }}</strong><br>
                    <small>Qty : {{ $detail->jumlah }}</small><br>
                    <small>Rp. {{ number_format($detail->harga_satuan, 0, ',', '.') }}</small>
                </div>
            </div>
            @endforeach

            <div class="bottom-bar">
                <span>Total : <strong>Rp. {{ number_format($order->total_harga, 0, ',', '.') }}</strong> ({{ $order->detailTransaksi->count() }} items)</span>
                
                {{-- TOMBOL AKSI --}}
                @if($order->status_pembayaran == 'Unpaid')
                    {{-- Jika belum bayar, arahkan kembali ke konfirmasi --}}
                    <a href="{{ route('payment.confirm', $order->id_transaksi) }}" class="pay-btn">Pay Now & Confirm</a>
                @else
                    {{-- Jika sudah lunas/batal, tampilkan detail biasa --}}
                    <span class="detail-btn" style="cursor: pointer; color: #ffffff;">Detail</span>
                @endif
            </div>
        </div>
    @endforeach

</div>

{{-- SCRIPT FILTER TABS --}}
<script>
    function filterOrders(category) {
        // 1. Reset class Active pada Tab
        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(t => t.classList.remove('active'));
        
        // 2. Set Active pada tab yang diklik
        event.target.classList.add('active');

        // 3. Filter Kartu Pesanan
        const cards = document.querySelectorAll('.order-card');
        const emptyMsg = document.querySelector('.no-orders-msg'); // Jika nanti mau ditambah pesan kosong per tab

        cards.forEach(card => {
            // Ambil kategori kartu dari attribute data-category
            const cardCat = card.getAttribute('data-category');

            if (category === 'all' || cardCat === category) {
                card.style.display = 'block'; // Tampilkan
            } else {
                card.style.display = 'none'; // Sembunyikan
            }
        });
    }
</script>

</body>
@endsection