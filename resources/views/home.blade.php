@extends('layouts.app')
@section('title', 'Home - Septa Classic Motor')
@section('content')

<section class="hero">
    <div class="hero-overlay"></div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Your Trusted Motorcycle Sparepart Partner</h1>
            <a href="{{ route('catalog') }}" class="btn btn-primary">Shop Now</a>
        </div>
    </div>
</section>

<section class="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-image">
                <img src="{{ asset('images/logo.png') }}" alt="Classic Motorcycle">
            </div>
            <div class="about-content">
                <h2 class="section-title">Merawat Nilai Klasik dengan Gaya Masa Kini</h2>
                <p class="about-text">
                    Sejak 2015, Septa Classic Motor menjadi bengkel spesialis restorasi, modifikasi, dan penyedia sparepart motor klasik. Kami percaya setiap motor punya cerita dan tugas kami adalah mengembalikan karakternya dengan sentuhan profesional.
                </p>
                <p class="about-text">
                    Dengan tim berpengalaman dan hasil kerja detail, kami berkomitmen menjaga warisan motor klasik agar tetap bergaya, berkarakter, dan bernilai.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="top-selling">
    <div class="container">
        <h2 class="section-title">TOP SELLING</h2>
        
        <div class="products-grid">
            @foreach($topProducts as $product)
            {{-- Tambahkan onclick agar bisa diklik ke detail --}}
            <div class="product-card" onclick="window.location.href='{{ route('product.detail', $product->id_produk) }}'" style="cursor: pointer;">
                
                <div class="product-image">
                    {{-- Logika Gambar Aman (Cek File Exists) --}}
                    @if($product->gambar && file_exists(public_path($product->gambar)))
                        <img src="{{ asset($product->gambar) }}" 
                             alt="{{ $product->nama_produk }}">
                    @else
                        {{-- Gambar Placeholder jika kosong --}}
                        <img src="https://via.placeholder.com/300x300?text=No+Image" 
                             alt="{{ $product->nama_produk }}">
                    @endif
                </div>

                <div class="product-info">
                    {{-- Nama Produk --}}
                    <h3 class="product-name">{{ $product->nama_produk }}</h3>
                    
                    <div class="product-price">
                        {{-- Harga Asli --}}
                        <span class="price-current">Rp. {{ number_format($product->harga, 0, ',', '.') }}</span>
                        
                        {{-- Bagian Diskon (Opsional: Hanya muncul jika Anda punya kolom harga_coret/diskon di DB) --}}
                        {{-- Karena di model sebelumnya belum ada, saya comment dulu agar tidak error --}}
                        {{-- 
                        @if(isset($product->harga_coret) && $product->harga_coret > $product->harga)
                            <span class="price-old">{{ number_format($product->harga_coret, 0, ',', '.') }}</span>
                            <span class="discount">Sale</span>
                        @endif 
                        --}}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="section-footer">
            <a href="{{ route('catalog') }}" class="btn btn-secondary">View All</a>
        </div>
    </div>
</section>

<section class="categories">
    <div class="container">
        <h2 class="section-title">BROWSE BY CATEGORY</h2>
        
        <div class="categories-grid">
            @foreach($categories as $category)
            <a href="{{ route('catalog', ['category' => $category['slug']]) }}" class="category-card">
                <img src="{{ asset($category['image']) }}" alt="{{ $category['name'] }}">
                <div class="category-overlay">
                    <h3 class="category-name">{{ $category['name'] }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

@endsection