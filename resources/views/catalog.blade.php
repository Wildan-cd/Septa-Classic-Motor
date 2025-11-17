@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
@endpush

@section('title', 'Catalog - Septa Classic Motor')

@section('content')

{{-- Breadcrumb --}}
<section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item active">Catalog</span>
        </nav>
    </div>
</section>

{{-- Catalog Section --}}
<section class="catalog-section">
    <div class="container">
        <div class="catalog-header">
            <h1 class="catalog-title">Product Catalog</h1>
            <p class="catalog-subtitle">Browse our collection of classic motorcycle parts and accessories</p>
        </div>

        <div class="catalog-container">
            {{-- Sidebar Filter --}}
            <aside class="catalog-sidebar">
                <div class="filter-section">
                    <h3 class="filter-title">Filters</h3>
                    
                    {{-- Search --}}
                    <div class="filter-group">
                        <label class="filter-label">Search</label>
                        <form action="{{ route('catalog') }}" method="GET" class="search-form">
                            <input type="text" 
                                   name="search" 
                                   class="filter-search" 
                                   placeholder="Search products..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="search-submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                    
                    {{-- Category Filter --}}
                    <div class="filter-group">
                        <label class="filter-label">Category</label>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="radio" name="category" value="" {{ !request('kategori') ? 'checked' : '' }} onchange="filterProducts()">
                                <span>All Categories</span>
                            </label>
                            @foreach($categories as $category)
                            <label class="filter-option">
                                <input type="radio" name="category" value="{{ $category }}" {{ request('kategori') == $category ? 'checked' : '' }} onchange="filterProducts()">
                                <span>{{ $category }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    {{-- Price Range --}}
                    <div class="filter-group">
                        <label class="filter-label">Price Range</label>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="radio" name="price" value="" {{ !request('price') ? 'checked' : '' }} onchange="filterProducts()">
                                <span>All Prices</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="0-100000" {{ request('price') == '0-100000' ? 'checked' : '' }} onchange="filterProducts()">
                                <span>< Rp 100.000</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="100000-200000" {{ request('price') == '100000-200000' ? 'checked' : '' }} onchange="filterProducts()">
                                <span>Rp 100.000 - 200.000</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="200000-500000" {{ request('price') == '200000-500000' ? 'checked' : '' }} onchange="filterProducts()">
                                <span>Rp 200.000 - 500.000</span>
                            </label>
                            <label class="filter-option">
                                <input type="radio" name="price" value="500000-999999999" {{ request('price') == '500000-999999999' ? 'checked' : '' }} onchange="filterProducts()">
                                <span>> Rp 500.000</span>
                            </label>
                        </div>
                    </div>
                    
                    {{-- Stock Filter --}}
                    <div class="filter-group">
                        <label class="filter-label">Availability</label>
                        <div class="filter-options">
                            <label class="filter-option">
                                <input type="checkbox" name="in_stock" {{ request('in_stock') ? 'checked' : '' }} onchange="filterProducts()">
                                <span>In Stock Only</span>
                            </label>
                        </div>
                    </div>
                    
                    {{-- Clear Filters --}}
                    <button type="button" class="btn-clear-filters" onclick="clearFilters()">
                        Clear All Filters
                    </button>
                </div>
            </aside>

            {{-- Products Grid --}}
            <div class="catalog-main">
                {{-- Sort & View Options --}}
                <div class="catalog-controls">
                    <div class="catalog-results">
                        Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> products
                    </div>
                    <div class="catalog-sort">
                        <label>Sort by:</label>
                        <select name="sort" onchange="sortProducts(this.value)" class="sort-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                {{-- Products Grid --}}
                @if($products->count() > 0)
                <div class="products-grid">
                    @foreach($products as $product)
                    <a href="{{ route('product.detail', ['id' => $product->id_produk]) }}" class="product-card-link">
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->gambar)
                                <img src="{{ asset($product->gambar) }}" alt="{{ $product->nama_produk }}">
                                @else
                                <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->nama_produk }}">
                                @endif
                                
                                @if($product->stok == 0)
                                <div class="product-badge out-of-stock">Out of Stock</div>
                                @elseif($product->stok < 5)
                                <div class="product-badge low-stock">Low Stock</div>
                                @endif
                            </div>
                            <div class="product-info">
                                <div class="product-category">{{ $product->Kategori }}</div>
                                <h3 class="product-name">{{ $product->nama_produk }}</h3>
                                <p class="product-description">{{ Str::limit($product->keterangan, 60) }}</p>
                                <div class="product-footer">
                                    <div class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                                    <div class="product-stock">
                                        @if($product->stok > 0)
                                        <span class="stock-available">{{ $product->stok }} in stock</span>
                                        @else
                                        <span class="stock-unavailable">Out of stock</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="pagination-wrapper">
                    {{ $products->links('pagination::default') }}
                </div>
                @else
                <div class="no-products">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <h3>No products found</h3>
                    <p>Try adjusting your filters or search terms</p>
                    <button onclick="clearFilters()" class="btn-primary">Clear Filters</button>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    function filterProducts() {
        const category = document.querySelector('input[name="category"]:checked')?.value || '';
        const price = document.querySelector('input[name="price"]:checked')?.value || '';
        const inStock = document.querySelector('input[name="in_stock"]')?.checked ? '1' : '';
        const search = document.querySelector('input[name="search"]')?.value || '';
        const sort = document.querySelector('select[name="sort"]')?.value || '';
        
        const params = new URLSearchParams();
        if (category) params.append('jenis', category);
        if (price) params.append('price', price);
        if (inStock) params.append('in_stock', inStock);
        if (search) params.append('search', search);
        if (sort) params.append('sort', sort);
        
        window.location.href = '{{ route("catalog") }}?' + params.toString();
    }
    
    function sortProducts(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', value);
        window.location.href = url.toString();
    }
    
    function clearFilters() {
        window.location.href = '{{ route("catalog") }}';
    }
</script>
@endpush