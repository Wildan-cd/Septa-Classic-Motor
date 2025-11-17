@extends('layouts.admin')

@section('title', 'Catalog')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/adminCatalog.css') }}">
@endpush

@section('content')
<div class="catalog-container">
    <div class="catalog-header">
        <h2 class="catalog-title">Catalog</h2>
        <a href="{{ route('admin.catalog.create') }}" class="btn-add-product">
            Add New Product
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
    @endif

    <div class="product-grid">
        @forelse($produks as $produk)
        <div class="product-card">
            <div class="product-header">
                <div class="product-info">
                    @if($produk->gambar && file_exists(public_path($produk->gambar)))
                        <img src="{{ asset($produk->gambar) }}" 
                             alt="{{ $produk->nama_produk }}"
                             class="product-image">
                    @else
                        <div class="product-placeholder">
                            📦
                        </div>
                    @endif
                    
                    <div class="product-details">
                        <h3 class="product-name">{{ $produk->nama_produk }}</h3>
                        <p class="product-category">{{ $produk->kategori }}</p>
                        <p class="product-price">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="product-menu">
                    <button onclick="toggleMenu({{ $produk->id_produk }})" class="menu-toggle">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                    <div id="menu-{{ $produk->id_produk }}" class="dropdown-menu">
                        <a href="{{ route('admin.catalog.edit', $produk->id_produk) }}" class="dropdown-item">
                            Edit
                        </a>
                        <button onclick="deleteProduct({{ $produk->id_produk }})" class="dropdown-item dropdown-item-danger">
                            Delete
                        </button>
                    </div>
                </div>
            </div>

            <div class="product-summary">
                <p class="summary-label">Summary</p>
                <p class="summary-text">{{ Str::limit($produk->keterangan ?? 'Tidak ada keterangan', 80) }}</p>
            </div>

            <div class="product-stats">
                <div class="stat-item">
                    <span class="stat-label">Sales</span>
                    <div class="stat-value">
                        <span class="stat-icon">▲</span>
                        <span class="stat-number">{{ $produk->total_sales ?? 0 }}</span>
                    </div>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Remaining Products</span>
                    <div class="stat-value">
                        <div class="progress-bar">
                            @php
                                $percentage = $produk->stok > 0 ? min(($produk->stok / 100) * 100, 100) : 0;
                            @endphp
                            <div class="progress-fill" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="stat-number">{{ $produk->stok }}</span>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            Belum ada produk
        </div>
        @endforelse
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <h3 class="modal-title">Konfirmasi Hapus</h3>
        <p class="modal-text">Apakah Anda yakin ingin menghapus produk ini?</p>
        <div class="modal-actions">
            <button onclick="closeDeleteModal()" class="btn-cancel">
                Batal
            </button>
            <form id="deleteForm" method="POST" style="flex: 1;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/adminCatalog.js') }}"></script>
@endpush
@endsection