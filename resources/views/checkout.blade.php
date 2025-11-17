@extends('layouts.app')

@section('title', 'Checkout - Septa Classic Motor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endpush

@section('content')

<div class="container">

    <!-- LEFT SIDE -->
    <div class="left-section">

        <h2>Checkout</h2>

        <div class="section-title">Contact</div>
        <div class="input-group">
            <label>Email</label>
            <input type="text" placeholder="yourmail@gmail.com">
        </div>

        <div class="section-title">Shipping</div>

        <div class="input-group">
            <label>Full Name</label>
            <input type="text" placeholder="Enter your name">
        </div>

        <div class="input-group">
            <label>Phone Number</label>
            <input type="text" placeholder="085xxxxxxx">
        </div>

        <div class="input-group">
            <label>Province</label>
            <select>
                <option>Select province</option>
            </select>
        </div>

        <div class="input-group">
            <label>City</label>
            <select>
                <option>Select city</option>
            </select>
        </div>

        <div class="input-group">
            <label>Address</label>
            <textarea placeholder="Write your full address"></textarea>
        </div>

        <div class="section-title">Payment</div>

        <div class="input-group">
            <label>Payment method</label>
            <select>
                <option>Bank Transfer</option>
            </select>
        </div>

    </div>


    <!-- RIGHT SIDE -->
    <div class="right-section">

        <!-- ITEM 1 -->
        <div class="item-box">
            <div class="item-img"></div>

            <div class="item-info">
                Nama Produk<br>
                <small>Qty: 1</small>
            </div>

            <div class="item-price">Rp 200.000</div>

            <div class="notes-box">
                <textarea placeholder="Notes (optional)"></textarea>
            </div>
        </div>

        <!-- ITEM 2 -->
        <div class="item-box">
            <div class="item-img"></div>

            <div class="item-info">
                Nama Produk Dua<br>
                <small>Qty: 2</small>
            </div>

            <div class="item-price">Rp 180.000</div>

            <div class="notes-box">
                <textarea placeholder="Notes (optional)"></textarea>
            </div>
        </div>

        <div class="price-row">
            <span>Subtotal:</span>
            <span>Rp 380.000</span>
        </div>

        <div class="price-row">
            <span>Shipping:</span>
            <span>Free</span>
        </div>

        <div class="total-row">
            <span>Total:</span>
            <span>Rp 380.000</span>
        </div>

        <button class="btn-order">Place Order</button>

    </div>

</div>

@endsection