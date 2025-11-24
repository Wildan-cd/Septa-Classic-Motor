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
    {{-- UBAH: $cart menjadi $cartItems --}}
    <p class="cart-count">You have {{ $cartItems->count() }} items in your cart</p>

    <div class="cart-list">
        {{-- UBAH: Loop variabel $cartItems --}}
        @foreach($cartItems as $item)
        
        {{-- UBAH: Akses object (->) bukan array ([]). Ambil data dari relasi produk --}}
        <div class="cart-item" data-id="{{ $item->id_produk }}" data-price="{{ $item->price }}">
            <div class="cart-item-left">
                <input type="checkbox" class="w-5 h-5 accent-black cursor-pointer"/>
                
                {{-- GAMBAR: Ambil dari $item->produk->gambar --}}
                @if($item->produk && $item->produk->gambar)
                    <img src="{{ asset($item->produk->gambar) }}" class="cart-img" alt="{{ $item->produk->nama_produk }}">
                @else
                    <img src="{{ asset('images/placeholder.jpg') }}" class="cart-img" alt="Product">
                @endif

                <div>
                    {{-- NAMA: Ambil dari $item->produk->nama_produk --}}
                    <h4 class="cart-item-title">{{ $item->produk->nama_produk ?? 'Produk tidak ditemukan' }}</h4>
                </div>
            </div>

            <div class="cart-item-right">
                <div class="qty-box">
                    {{-- MINUS BUTTON --}}
                    <button type="button"
                        class="qty-btn minus"
                        data-id="{{ $item->id_produk }}"
                        aria-label="decrease">-</button>

                    {{-- QTY DISPLAY: Gunakan ->quantity --}}
                    <span class="qty-number" id="qty-{{ $item->id_produk }}">
                        {{ $item->quantity }}
                    </span>

                    {{-- PLUS BUTTON --}}
                    <button type="button"
                        class="qty-btn plus"
                        data-id="{{ $item->id_produk }}"
                        aria-label="increase">+</button>
                </div>

                {{-- PRICE SUBTOTAL: Gunakan ->price * ->quantity --}}
                <p class="price" id="subtotal-{{ $item->id_produk }}">
                    Rp. {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                </p>

                {{-- HIDDEN UNIT PRICE --}}
                <input type="hidden" id="unitprice-{{ $item->id_produk }}" value="{{ $item->price }}">

                {{-- REMOVE FORM --}}
                {{-- Pastikan route 'cart.remove' sudah dibuat di web.php --}}
                {{-- Form Hapus Produk --}}
                <form action="{{ route('cart.remove') }}" method="POST" class="delete-form" style="display:inline;">
                    @csrf
                    @method('DELETE') 
                    <input type="hidden" name="id" value="{{ $item->id_produk }}">
                    
                    {{-- HAPUS onclick="return confirm..." dari sini --}}
                    <button type="submit" class="delete-btn" title="Hapus Produk">
                        🗑
                    </button>
                </form>
            </div>
        </div>
        @endforeach
        
        @if($cartItems->isEmpty())
            <p style="text-align:center; margin: 20px 0;">Keranjang belanja Anda kosong.</p>
        @endif
    </div>

    {{-- CHECKOUT AREA --}}
    <div class="checkout-area" style="display:flex; justify-content:space-between; align-items:center; margin-top:24px;">
        <div></div>

        <div class="checkout-box" style="text-align:right;">
            <p class="subtotal-text" style="margin-bottom:8px;">
                {{-- SUBTOTAL GLOBAL: Hitung ulang pakai collection sum --}}
                Subtotal: <strong id="cartSubtotal">
                    Rp. {{ number_format($cartItems->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }}
                </strong>
            </p>

            <div class="checkout-box">
                {{-- Button href tidak valid, ganti jadi <a> --}}
                <a href="{{ route('checkout') }}" class="checkout-btn" style="text-decoration:none; display:inline-block; padding:10px 20px; background:black; color:white;">Check Out</a>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
const CSRF_TOKEN = '{{ csrf_token() }}'; // Cara ambil token yang lebih simpel

function formatRupiahNumber(number) {
    return 'Rp. ' + Number(number).toLocaleString('id-ID');
}

function recalcCartSubtotal() {
    let total = 0;
    // Loop semua elemen yang punya ID diawali 'subtotal-'
    document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
        // Hapus "Rp. " dan titik, ambil angkanya saja
        const textVal = el.innerText.replace(/[^\d]/g, '');
        total += parseInt(textVal || 0, 10);
    });
    document.getElementById('cartSubtotal').innerText = formatRupiahNumber(total);
}

function sendUpdateToServer(id_produk, qty) {
    // Pastikan route cart.update ada di web.php
    fetch("{{ route('cart.update') }}", {
        method: "POST", // Atau PATCH
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": CSRF_TOKEN
        },
        body: JSON.stringify({
            id_produk: id_produk,
            quantity: qty // Sesuaikan key dengan controller (quantity)
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'error') {
            alert(data.message);
            location.reload(); // Reload jika stok habis/error
        }
        console.log('Updated:', data);
    })
    .catch(err => console.error('Error:', err));
}

function updateQtyUI(id_produk, newQty) {
    if (newQty < 1) return; // Jangan biarkan 0 di frontend

    // 1. Update angka di layar
    const qtyEl = document.getElementById(`qty-${id_produk}`);
    if (qtyEl) qtyEl.innerText = newQty;

    // 2. Hitung subtotal per item
    const unitInput = document.getElementById(`unitprice-${id_produk}`);
    const unitPrice = unitInput ? parseInt(unitInput.value, 10) : 0;
    const newSubtotal = unitPrice * newQty;

    const subtotalEl = document.getElementById(`subtotal-${id_produk}`);
    if (subtotalEl) subtotalEl.innerText = formatRupiahNumber(newSubtotal);

    // 3. Hitung total belanja
    recalcCartSubtotal();

    // 4. Kirim ke server (Database)
    sendUpdateToServer(id_produk, newQty);
}

document.addEventListener('DOMContentLoaded', function() {
    // Event Listener Tombol Plus
    document.querySelectorAll('.qty-btn.plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const qtyEl = document.getElementById('qty-' + id);
            let qty = parseInt(qtyEl.innerText || '1', 10);
            updateQtyUI(id, qty + 1);
        });
    });

    // Event Listener Tombol Minus
    document.querySelectorAll('.qty-btn.minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const qtyEl = document.getElementById('qty-' + id);
            let qty = parseInt(qtyEl.innerText || '1', 10);
            if (qty > 1) {
                updateQtyUI(id, qty - 1);
            }
        });
    });
    
    // Hitung ulang saat pertama load (opsional)
    recalcCartSubtotal();
});

document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua form yang punya class 'delete-form'
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Cegah form terkirim langsung

                Swal.fire({
                    title: 'Hapus Produk?',
                    text: "Produk ini akan dihapus dari keranjang Anda.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Warna Merah
                    cancelButtonColor: '#3085d6', // Warna Biru
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan Loading sebentar
                        Swal.fire({
                            title: 'Menghapus...',
                            timer: 1000,
                            didOpen: () => Swal.showLoading()
                        });
                        
                        // Kirim form secara manual
                        this.submit(); 
                    }
                });
            });
        });
    });
</script>
@endpush