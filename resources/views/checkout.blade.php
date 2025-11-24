@extends('layouts.app')

@section('title', 'Check Out')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endpush

@section('content')
<div class="checkout-container">
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
        {{-- Pastikan route cart index bernama 'cart.index' --}}
        <a href="{{ route('cart.index') }}" class="breadcrumb-link">Cart</a>
        <span class="breadcrumb-separator">></span>
        <span class="breadcrumb-current">Check Out</span>
    </div>

    <h1 class="checkout-title">Check Out</h1>

    @if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <ul class="error-list">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="checkout-content">
        <div class="billing-section">
            <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
                @csrf
                
                <div class="form-group">
                    <label for="first_name" class="form-label">First Name*</label>
                    <input type="text" 
                           name="first_name" 
                           id="first_name" 
                           class="form-input"
                           value="{{ old('first_name', Auth::user()->name ?? '') }}"
                           required>
                </div>

                <div class="form-group">
                    <label for="company_name" class="form-label">Company Name</label>
                    <input type="text" 
                           name="company_name" 
                           id="company_name" 
                           class="form-input"
                           value="{{ old('company_name') }}">
                </div>

                <div class="form-group">
                    <label for="street_address" class="form-label">Street Address*</label>
                    <input type="text" 
                           name="street_address" 
                           id="street_address" 
                           class="form-input"
                           value="{{ old('street_address') }}"
                           required>
                </div>

                <div class="form-group">
                    <label for="apartment" class="form-label">Apartment, floor, etc. (optional)</label>
                    <input type="text" 
                           name="apartment" 
                           id="apartment" 
                           class="form-input"
                           value="{{ old('apartment') }}">
                </div>

                <div class="form-group">
                    <label for="town_city" class="form-label">Town/City*</label>
                    <input type="text" 
                           name="town_city" 
                           id="town_city" 
                           class="form-input"
                           value="{{ old('town_city') }}"
                           required>
                </div>

                <div class="form-group">
                    <label for="phone_number" class="form-label">Phone Number*</label>
                    <input type="tel" 
                           name="phone_number" 
                           id="phone_number" 
                           class="form-input"
                           value="{{ old('phone_number') }}"
                           required>
                </div>

                <div class="form-group">
                    <label for="email_address" class="form-label">Email Address*</label>
                    <input type="email" 
                           name="email_address" 
                           id="email_address" 
                           class="form-input"
                           value="{{ old('email_address', Auth::user()->email ?? '') }}"
                           required>
                </div>

                <div class="form-group-checkbox">
                    <input type="checkbox" 
                           name="save_info" 
                           id="save_info"
                           {{ old('save_info') ? 'checked' : '' }}>
                    <label for="save_info" class="checkbox-label">
                        Save this information for faster check-out next time
                    </label>
                </div>
            </form>
        </div>

        <div class="order-summary">
            <div class="summary-items">
                @foreach($cartItems as $item)
                <div class="summary-item">
                    <div class="item-image-wrapper">
                        {{-- LOGIC GAMBAR DIPERBAIKI: Menggunakan asset storage --}}
                        @if($item->produk && $item->produk->gambar)
                            <img src="{{ asset($item->produk->gambar) }}" 
                                 alt="{{ $item->produk->nama_produk }}"
                                 class="item-image">
                        @else
                            <img src="{{ asset('images/placeholder.jpg') }}" 
                                 alt="Produk" 
                                 class="item-image">
                        @endif
                    </div>
                    <div class="item-details">
                        {{-- AKSES DATA OBJEK: ->nama_produk --}}
                        <h4 class="item-name">{{ $item->produk->nama_produk ?? 'Produk dihapus' }}</h4>
                        <p class="item-notes">Qty: {{ $item->quantity }}</p>
                    </div>
                    <div class="item-price">
                        {{-- HITUNG SUBTOTAL MANUAL: price * quantity --}}
                        Rp. {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="price-breakdown">
                <div class="price-row">
                    <span class="price-label">Subtotal</span>
                    <span class="price-value">Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="price-row">
                    <span class="price-label">Shipping</span>
                    <span class="price-value">Rp. {{ number_format($shipping, 0, ',', '.') }}</span>
                </div>
                <div class="price-row price-total">
                    <span class="price-label-total">Total</span>
                    <span class="price-value-total">Rp. {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <button type="submit" form="checkoutForm" class="btn-place-order">
                Place Order
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/checkout.js') }}"></script>
@endpush
@endsection