@extends('layouts.admin')

@section('title', $produk ? 'Edit Product' : 'Add Product')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/adminForm.css') }}">
@endpush

@section('content')
<div class="form-container">
    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="{{ route('admin.catalog.index') }}" class="breadcrumb-link">Catalog</a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">{{ $produk ? 'Edit Product' : 'Add Product' }}</span>
    </nav>

    <!-- Form Card -->
    <div class="form-card">
        <div class="form-header">
            <h2 class="form-title">{{ $produk ? 'Edit Product' : 'Add Product' }}</h2>
            <a href="{{ route('admin.catalog.index') }}" class="btn-close">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            <ul class="error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ $produk ? route('admin.catalog.update', $produk->id_produk) : route('admin.catalog.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="product-form">
            @csrf
            @if($produk)
                @method('PUT')
            @endif

            <!-- Product Name -->
            <div class="form-group">
                <label for="nama_produk" class="form-label">
                    Product Name <span class="required">*</span>
                </label>
                <input type="text" 
                       name="nama_produk" 
                       id="nama_produk" 
                       value="{{ old('nama_produk', $produk->nama_produk ?? '') }}"
                       class="form-input" 
                       placeholder="Masukkan nama produk"
                       required>
                @error('nama_produk')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="keterangan" class="form-label">
                    Description
                </label>
                <textarea name="keterangan" 
                          id="keterangan" 
                          rows="4"
                          class="form-textarea" 
                          placeholder="Masukkan deskripsi produk">{{ old('keterangan', $produk->keterangan ?? '') }}</textarea>
                @error('keterangan')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div class="form-group">
                <label for="kategori" class="form-label">
                    Category <span class="required">*</span>
                </label>
                <input type="text" 
                       name="kategori" 
                       id="kategori" 
                       value="{{ old('kategori', $produk->kategori ?? '') }}"
                       class="form-input" 
                       placeholder="Contoh: Battery, Spare Part"
                       required>
                @error('kategori')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock and Price -->
            <div class="form-row">
                <!-- Stock -->
                <div class="form-group">
                    <label for="stok" class="form-label">
                        Stock Quantity <span class="required">*</span>
                    </label>
                    <input type="number" 
                           name="stok" 
                           id="stok" 
                           min="0"
                           value="{{ old('stok', $produk->stok ?? '') }}"
                           class="form-input" 
                           placeholder="0"
                           required>
                    @error('stok')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div class="form-group">
                    <label for="harga" class="form-label">
                        Price (Rp) <span class="required">*</span>
                    </label>
                    <input type="number" 
                           name="harga" 
                           id="harga" 
                           min="0"
                           step="0.01"
                           value="{{ old('harga', $produk->harga ?? '') }}"
                           class="form-input" 
                           placeholder="0"
                           required>
                    @error('harga')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Product Image -->
            <div class="form-group">
                <label class="form-label">Product Image</label>
                
                <!-- Current Image Preview (Edit Mode) -->
                @if($produk && $produk->gambar)
                <div class="current-image-wrapper">
                    <p class="current-image-label">Current Image:</p>
                    <div class="current-image-container">
                        <img src="{{ asset($produk->gambar) }}" 
                             alt="{{ $produk->nama_produk }}"
                             class="current-image">
                        <span class="current-badge">Current</span>
                    </div>
                    <p class="current-image-note">Upload gambar baru untuk mengganti</p>
                </div>
                @endif

                <!-- Image Upload Area -->
                <div class="upload-area">
                    <div class="upload-icon">
                        <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <p class="upload-text">Drop your image here, or Browse</p>
                    <p class="upload-hint">jpeg, jpg, png are allowed (Max: 2MB)</p>
                    
                    <input type="file" 
                           name="gambar" 
                           id="gambar"
                           accept="image/jpeg,image/jpg,image/png"
                           class="file-input"
                           onchange="previewImage(event)">
                    
                    <label for="gambar" class="btn-browse">
                        Browse Files
                    </label>
                </div>

                <!-- Image Preview -->
                <div id="imagePreview" class="image-preview">
                    <p class="preview-label">New Image Preview:</p>
                    <div class="preview-container">
                        <img id="previewImg" src="" alt="Preview" class="preview-image">
                        <button type="button" 
                                onclick="removePreview()"
                                class="btn-remove-preview">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                @error('gambar')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="form-actions">
                <a href="{{ route('admin.catalog.index') }}" class="btn-cancel-form">
                    CANCEL
                </a>
                <button type="submit" class="btn-submit">
                    {{ $produk ? 'UPDATE' : 'ADD PRODUCT' }}
                </button>
            </div>
        </form>
    </div>

    @if($produk)
    <!-- Additional Info Card -->
    <div class="info-card">
        <div class="info-content">
            <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="info-text">
                <p class="info-title">Product Information</p>
                <p class="info-item">
                    Product ID: <span class="info-value">{{ $produk->id_produk }}</span>
                </p>
                <p class="info-item">
                    Total Sales: <span class="info-value">{{ $produk->detailTransaksi()->sum('jumlah') ?? 0 }}</span> units
                </p>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="{{ asset('js/adminForm.js') }}"></script>
@endpush
@endsection