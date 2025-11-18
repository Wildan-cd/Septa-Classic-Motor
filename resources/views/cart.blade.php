@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/cart.css') }}">
@endpush

@section('content')

<div class="cart-container">

    <h1 class="cart-title">Cart</h1>
    <a href="{{ route('catalog') }}" class="back-link">‹ Shopping Continue</a>

    <hr>

    <h3 class="cart-subtitle">Shopping cart</h3>
    <p class="cart-count">You have {{ count($cart) }} items in your cart</p>

    <div class="cart-list">
        @foreach($cart as $item)
        {{-- cart-item: data attributes for JS --}}
        <div class="cart-item" data-id="{{ $item['id_produk'] }}" data-price="{{ $item['harga'] }}">
            <div class="cart-item-left">
                <input type="checkbox" class="w-5 h-5 accent-black cursor-pointer"/>
                <img src="/storage/{{ $item['gambar'] }}" class="cart-img" alt="{{ $item['nama'] }}">
                <div>
                    <h4 class="cart-item-title">{{ $item['nama'] }}</h4>
                </div>
            </div>

            <div class="cart-item-right">
                <div class="qty-box">
                    {{-- minus button --}}
                    <button type="button"
                        class="qty-btn minus"
                        data-id="{{ $item['id_produk'] }}"
                        aria-label="decrease">-</button>

                    {{-- qty display (span) --}}
                    <span class="qty-number" id="qty-{{ $item['id_produk'] }}">
                        {{ $item['qty'] }}
                    </span>

                    {{-- plus button --}}
                    <button type="button"
                        class="qty-btn plus"
                        data-id="{{ $item['id_produk'] }}"
                        aria-label="increase">+</button>
                </div>

                {{-- price subtotal per item --}}
                <p class="price" id="subtotal-{{ $item['id_produk'] }}">
                    Rp. {{ number_format($item['harga'] * $item['qty'],0,',','.') }}
                </p>

                {{-- hidden element with unit price for JS --}}
                <input type="hidden" id="unitprice-{{ $item['id_produk'] }}" value="{{ $item['harga'] }}">

                {{-- Remove form (POST) - send id_produk --}}
                <form action="{{ route('cart.remove') }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item['id_produk'] }}">
                    <button class="delete-btn" title="Remove">🗑</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Checkout area with subtotal at the side --}}
    <div class="checkout-area" style="display:flex; justify-content:space-between; align-items:center; margin-top:24px;">
        <div>
            {{-- left side: empty or summary --}}
        </div>

        <div class="checkout-box" style="text-align:right;">
            <p class="subtotal-text" style="margin-bottom:8px;">
                Subtotal: <strong id="cartSubtotal">
                    Rp. {{ number_format(collect($cart)->sum(fn($i) => $i['harga'] * $i['qty']), 0, ',', '.') }}
                </strong>
            </p>

                <div class="checkout-box">
                    <a href="{{ route('checkout') }}" class="checkout-btn" >Check Out</a>
                </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// CSRF token (Blade -> JS)
const CSRF_TOKEN = @json(csrf_token());

// Helper: format number to "Rp. 1.234"
function formatRupiahNumber(number) {
    return 'Rp. ' + Number(number).toLocaleString('id-ID');
}

// Recalculate overall subtotal (sum of subtotals shown)
function recalcCartSubtotal() {
    let total = 0;
    document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
        // el text like "Rp. 1.234", remove non digits
        const digits = el.innerText.replace(/\D/g, '');
        total += parseInt(digits || 0, 10);
    });
    document.getElementById('cartSubtotal').innerText = formatRupiahNumber(total);
    return total;
}

// Update server (session/db) via POST, non-blocking (fire-and-forget but handle errors)
function sendUpdateToServer(id_produk, qty) {
    fetch("{{ route('cart.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": CSRF_TOKEN
        },
        body: JSON.stringify({
            id_produk: id_produk,
            qty: qty
        })
    })
    .then(res => res.json())
    .then(data => {
        // optionally handle server errors (show toast)
        if (data && data.success === false) {
            console.error('Update failed', data);
            // optionally show message to user
        }
    })
    .catch(err => {
        console.error('Fetch error', err);
    });
}

// Update quantity (called by buttons)
function updateQtyUI(id_produk, newQty) {
    if (newQty < 1) newQty = 1;

    // update qty displayed
    const qtyEl = document.getElementById(`qty-${id_produk}`);
    if (!qtyEl) return;
    qtyEl.innerText = newQty;

    // compute new subtotal = unitPrice * qty
    const unitInput = document.getElementById(`unitprice-${id_produk}`);
    const unitPrice = unitInput ? parseInt(unitInput.value, 10) : parseInt(document.querySelector(`[data-id="${id_produk}"]`)?.dataset.price || 0, 10);
    const newSubtotal = unitPrice * newQty;

    const subtotalEl = document.getElementById(`subtotal-${id_produk}`);
    if (subtotalEl) subtotalEl.innerText = formatRupiahNumber(newSubtotal);

    // recalc grand total
    recalcCartSubtotal();

    // send to server
    sendUpdateToServer(id_produk, newQty);
}

// attach events (plus/minus)
function attachQtyEvents() {
    document.querySelectorAll('.qty-btn.plus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const id = this.dataset.id;
            const qtyEl = document.getElementById('qty-' + id);
            let qty = parseInt(qtyEl.innerText || '0', 10);
            qty = qty + 1;
            updateQtyUI(id, qty);
        });
    });

    document.querySelectorAll('.qty-btn.minus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const id = this.dataset.id;
            const qtyEl = document.getElementById('qty-' + id);
            let qty = parseInt(qtyEl.innerText || '0', 10);
            qty = Math.max(1, qty - 1);
            updateQtyUI(id, qty);
        });
    });
}

// run on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    attachQtyEvents();
    recalcCartSubtotal();
});
</script>
@endpush
