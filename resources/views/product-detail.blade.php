@extends('layouts.app')

@section('title', $product->nama_produk . ' - Septa Classic Motor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
@endpush

@section('content')

{{-- Back Button & Breadcrumb --}}
<section class="product-breadcrumb">
    <div class="container">
        <a href="{{ route('catalog') }}" class="back-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
        </a>
        <nav class="breadcrumb-inline">
            <a href="{{ route('catalog') }}">Catalog</a>
            <span>/</span>
            <span>Produk</span>
        </nav>
    </div>
</section>

{{-- Product Detail Section --}}
<section class="product-detail-new">
    <div class="container">
        <div class="product-layout">
            {{-- Left: Images Gallery --}}
            <div class="product-gallery">
                {{-- Thumbnail List --}}
                <div class="gallery-thumbnails">
                    @if($product->gambar)
                        <div class="thumbnail active" onclick="changeMainImage('{{ asset('storage/' . $product->gambar) }}', this)">
                            <img src="{{ asset('storage/' . $product->gambar) }}" alt="Thumbnail 1">
                        </div>
                    @else
                        <div class="thumbnail active" onclick="changeMainImage('{{ asset('images/placeholder.jpg') }}', this)">
                            <img src="{{ asset('images/placeholder.jpg') }}" alt="Thumbnail 1">
                        </div>
                    @endif
                    {{-- Placeholder for more images --}}
                    <div class="thumbnail" onclick="changeMainImage('{{ asset('images/placeholder.jpg') }}', this)">
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Thumbnail 2">
                    </div>
                    <div class="thumbnail" onclick="changeMainImage('{{ asset('images/placeholder.jpg') }}', this)">
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="Thumbnail 3">
                    </div>
                </div>
                
                {{-- Main Image --}}
                <div class="gallery-main">
                    @if($product->gambar)
                    <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" id="mainProductImage">
                    @else
                    <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->nama_produk }}" id="mainProductImage">
                    @endif
                </div>
            </div>

            {{-- Right: Product Info --}}
            <div class="product-info-new">
                <h1 class="product-title-new">{{ $product->nama_produk }}</h1>
                
                <div class="product-price-new">
                    <span class="price-main">Rp. {{ number_format($product->harga, 0, ',', '.') }}</span>
                    @if(false) {{-- Placeholder for old price --}}
                    <span class="price-old">Rp. 200.000</span>
                    <span class="price-discount">-20%</span>
                    @endif
                </div>
                
                <div class="product-short-desc">
                    {{ Str::limit($product->keterangan, 200) }}
                </div>
                
                {{-- Quantity & Add to Cart --}}
                <div class="product-actions-new">
                    <div class="quantity-control">
                        <button type="button" class="qty-decrease" onclick="decreaseQty()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                        <input type="number" id="productQty" value="1" min="1" max="{{ $product->stok }}" readonly>
                        <button type="button" class="qty-increase" onclick="increaseQty()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </button>
                    </div>
                    
                    <button class="btn-add-to-cart" onclick="addToCart({{ $product->id_produk }})">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>
        
        {{-- Product Details Tab --}}
        <div class="product-tabs">
            <div class="tabs-header">
                <button class="tab-btn active" onclick="openTab(event, 'details')">Product Details</button>
            </div>
            
            <div id="details" class="tab-content active">
                <h3>Product Details</h3>
                <div class="product-details-content">
                    <p><strong>Deskripsi Produk:</strong></p>
                    <p>{{ $product->keterangan }}</p>
                    
                    <p><strong>Spesifikasi:</strong></p>
                    <ul class="specs-list">
                        <li><strong>Kategori:</strong> {{ $product->Kategori }}</li>
                        <li><strong>Harga:</strong> Rp. {{ number_format($product->harga, 0, ',', '.') }}</li>
                        <li><strong>Stok:</strong> {{ $product->stok }} unit</li>
                        <li><strong>Bahan:</strong> High Quality Material</li>
                        <li><strong>Warna:</strong> Sesuai Gambar</li>
                        <li><strong>Kondisi:</strong> Baru / Original</li>
                    </ul>
                    
                    <p><strong>Keunggulan Produk:</strong></p>
                    <ul class="features-list">
                        <li>✓ Original quality untuk motor klasik</li>
                        <li>✓ Tahan lama dan berkualitas tinggi</li>
                        <li>✓ Mudah dipasang dan digunakan</li>
                        <li>✓ Cocok untuk berbagai model motor klasik</li>
                        <li>✓ Garansi kualitas terjamin</li>
                        <li>✓ Harga terjangkau dan kompetitif</li>
                    </ul>
                    
                    <p><strong>Catatan:</strong></p>
                    <p>Pastikan untuk mengecek kompatibilitas produk dengan motor Anda sebelum membeli. Untuk konsultasi lebih lanjut, silakan hubungi customer service kami.</p>
                </div>
            </div>
        </div>
        
        {{-- You Might Also Like --}}
        <div class="related-section">
            <h2 class="related-title">You might also like</h2>
            <div class="related-grid">
                @foreach($relatedProducts as $related)
                <a href="{{ route('product.detail', $related->id_produk) }}" class="related-card">
                    <div class="related-image">
                        @if($related->gambar)
                        <img src="{{ asset('storage/' . $related->gambar) }}" alt="{{ $related->nama_produk }}">
                        @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $related->nama_produk }}">
                        @endif
                    </div>
                    <div class="related-info">
                        <h4>{{ $related->nama_produk }}</h4>
                        <div class="related-price">
                            <span>Rp. {{ number_format($related->harga, 0, ',', '.') }}</span>
                            @if(false) {{-- Placeholder for discount --}}
                            <span class="discount-badge">-20%</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    const maxStock = {{ $product->stok }};
    
    function changeMainImage(imageSrc, thumbnail) {
        document.getElementById('mainProductImage').src = imageSrc;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
    }
    
    function increaseQty() {
        const input = document.getElementById('productQty');
        let value = parseInt(input.value);
        if (value < maxStock) {
            input.value = value + 1;
        }
    }
    
    function decreaseQty() {
        const input = document.getElementById('productQty');
        let value = parseInt(input.value);
        if (value > 1) {
            input.value = value - 1;
        }
    }
    
    function addToCart(productId) {
        fetch("{{ route('cart.add') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                id_produk: productId,
                qty: 1
            })
        })
        .then(response => {
            if (response.status === 401) {
                // Unauthorized - belum login
                return response.json().then(data => {
                    alert(data.message);
                    window.location.href = data.redirect;
                    throw new Error('Unauthorized');
                });
            }
            return response.json();
        })
        .then(data => {
            if(data.status === 'success') {
                alert(data.message);
                // Update cart count jika ada
                updateCartCount(data.cart_count);
            } else if(data.status === 'error') {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if(error.message !== 'Unauthorized') {
                alert('Terjadi kesalahan!');
            }
        });
    }

    function updateCartCount(count) {
        const cartBadge = document.querySelector('.cart-count');
        if(cartBadge) {
            cartBadge.textContent = count;
        }
    }
    
    function openTab(evt, tabName) {
        const tabContent = document.getElementsByClassName("tab-content");
        for (let i = 0; i < tabContent.length; i++) {
            tabContent[i].classList.remove('active');
        }
        
        const tabBtn = document.getElementsByClassName("tab-btn");
        for (let i = 0; i < tabBtn.length; i++) {
            tabBtn[i].classList.remove('active');
        }
        
        document.getElementById(tabName).classList.add('active');
        evt.currentTarget.classList.add('active');
    }
</script>
@endpush