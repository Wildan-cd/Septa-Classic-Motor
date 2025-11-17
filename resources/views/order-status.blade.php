@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/order-status.css') }}">
@endpush

@section('title', 'Catalog - Septa Classic Motor')

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

    <div class="tabs">
        <div class="tab active">All Order (10)</div>
        <div class="tab">Awaiting pay (2)</div>
        <div class="tab">Cenceled (5)</div>
        <div class="tab">On Deliver (1)</div>
        <div class="tab">Completed (1)</div>
    </div>

    {{-- ORDER CARD Example --}}
    <div class="order-card">
        <div class="order-top">
            <div>
                <strong>Order ID</strong><br>
                SCM25-93288930
            </div>
            <div class="badge completed">Completed</div>
        </div>

        @for ($i = 0; $i < 3; $i++)
        <div class="product-box">
            <div class="product-img"></div>
            <div class="product-info">
                Nama Produk<br>
                <small>Qty : 1</small>
            </div>
        </div>
        @endfor

        <div class="bottom-bar">
            <span>Total : Rp. 200.000 (3 items)</span>
            <span class="detail-btn">Detail</span>
        </div>
    </div>

    {{-- ORDER CARD 2 --}}
    <div class="order-card">
        <div class="order-top">
            <div>
                <strong>Order ID</strong><br>
                SCM25-93288930
            </div>
            <div class="badge ondeliver">On Deliver</div>
        </div>

        @for ($i = 0; $i < 3; $i++)
        <div class="product-box">
            <div class="product-img"></div>
            <div class="product-info">
                Nama Produk<br>
                <small>Qty : 1</small>
            </div>
        </div>
        @endfor

        <div class="bottom-bar">
            <span>Total : Rp. 200.000 (3 items)</span>
            <span class="detail-btn">Detail</span>
        </div>
    </div>

</div>

<script src="{{ asset('js/order-status.js') }}"></script>
</body>
@endsection