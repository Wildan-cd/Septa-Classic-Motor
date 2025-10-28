@extends('layouts.app')

@section('title', 'About Us - Septa Classic Motor')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')

{{-- Breadcrumb --}}
<section class="breadcrumb-section">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-item">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-item active">About</span>
        </nav>
    </div>
</section>

{{-- Section 1: About Content --}}
<section class="about-detail">
    <div class="container">
        <div class="about-detail-grid">
            <div class="about-detail-content">
                <h1 class="page-title">Your Reliable Partner for Quality Classic Motorcycle Parts and Restoration</h1>
                
                <div class="about-detail-text">
                    <p>
                        Didirikan pada tahun 2015, Septa Classic Motor berawal dari kecintaan terhadap motor klasik dan semangat untuk menjaga warisan otomotif tetap hidup di jalanan. Kami memulai dari bengkel kecil yang fokus pada perawatan dan restorasi motor lawas, hingga kini berkembang menjadi penyedia suku cadang dan layanan perbaikan yang dipercaya banyak penggemar motor klasik.
                    </p>
                    
                    <p>
                        Selama bertahun-tahun, kami terus berkomitmen memberikan produk berkualitas, pelayanan yang jujur, serta hasil kerja yang memuaskan. Setiap motor yang kami tangani memiliki cerita dan kami hadir untuk membantu pemiliknya menjaga nilai dan keasliannya.
                    </p>
                    
                    <p>
                        Bagi kami, Septa Classic Motor bukan sekadar bengkel, tapi rumah bagi para pencinta motor klasik yang ingin mempertahankan pesona masa lalu dengan kualitas masa kini.
                    </p>
                </div>
            </div>
            
            <div class="about-detail-images">
                <div class="image-grid">
                    <div class="image-item large">
                        <img src="{{ asset('images/layanan.jpg') }}" alt="Workshop Septa Classic Motor">
                    </div>
                    <div class="image-item">
                        <img src="{{ asset('images/modif3.jpg') }}" alt="Classic Motorcycle">
                    </div>
                    <div class="image-item">
                        <img src="{{ asset('images/resto1.jpg') }}" alt="Restored Motorcycle">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Section 2: Our Services --}}
<section class="our-services">
    <div class="container">
        <h2 class="section-title">OUR SERVICES</h2>
        
        <div class="services-grid">
            <div class="service-card black">
                <h3 class="service-title">Spare Part Store</h3>
                <p class="service-desc">Tersedia berbagai suku cadang dan aksesori motor klasik dengan kualitas terbaik.</p>
            </div>
            
            <div class="service-card gray">
                <h3 class="service-title">Installation Service</h3>
                <p class="service-desc">Layanan pemasangan profesional untuk memastikan performa motor tetap optimal.</p>
            </div>
            
            <div class="service-card gray">
                <h3 class="service-title">Motorcycle Maintenance</h3>
                <p class="service-desc">Perawatan rutin dan perbaikan ringan untuk menjaga kondisi motor tetap prima.</p>
            </div>
            
            <div class="service-card black">
                <h3 class="service-title">Consultation & Recommendations</h3>
                <p class="service-desc">Bantuan dalam memilih suku cadang dan layanan sesuai kebutuhan motormu.</p>
            </div>
        </div>
    </div>
</section>

{{-- Section 3: Get In Touch --}}
<section class="contact-section">
    <div class="container">
        <h2 class="section-title">GET IN TOUCH WITH US</h2>
        
        <div class="contact-grid">
            <div class="contact-card gray">
                <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                </div>
                <h3 class="contact-title">Phone</h3>
                <a href="tel:+6282234322320" class="contact-info">+6282234322320</a>
            </div>
            
            <div class="contact-card black">
                <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                        <path d="m3 6 9 6 9-6"></path>
                    </svg>
                </div>
                <h3 class="contact-title">Email</h3>
                <a href="mailto:kwbmotorclassic@gmail.com" class="contact-info">kwbmotorclassic@gmail.com</a>
            </div>
            
            <div class="contact-card gray">
                <div class="contact-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </div>
                <h3 class="contact-title">Store</h3>
                <p class="contact-info">Jl. Bukit Berbunga No. 115, Sidomulyo, Kec. Batu, Kota Batu, Jawa Timur</p>
            </div>
        </div>
        
        {{-- Google Maps --}}
        <div class="map-container">
            <div id="map"></div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Initialize Google Maps
    function initMap() {
        // Koordinat Septa Classic Motor - Jl. Bukit Berbunga No.115, Batu
        const location = { lat: -7.8671, lng: 112.5241 };
        
        const map = new google.maps.Map(document.getElementById('map'), {
            zoom: 16,
            center: location,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true,
            styles: [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'on' }]
                }
            ]
        });
        
        const marker = new google.maps.Marker({
            position: location,
            map: map,
            title: 'Septa Classic Motor',
            animation: google.maps.Animation.DROP,
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
                scaledSize: new google.maps.Size(40, 40)
            }
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="padding: 15px; max-width: 250px;">
                    <h3 style="margin: 0 0 10px 0; font-size: 18px; font-weight: bold; color: #000;">Septa Classic Motor</h3>
                    <p style="margin: 0 0 10px 0; font-size: 14px; color: #666; line-height: 1.5;">
                        Jl. Bukit Berbunga No. 115<br>
                        Sidomulyo, Kec. Batu<br>
                        Kota Batu, Jawa Timur 65317
                    </p>
                    <p style="margin: 0 0 10px 0; font-size: 13px; color: #666;">
                        <strong>Jam Buka:</strong> 09.00 - 20.00 WIB
                    </p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=-7.8671,112.5241" 
                       target="_blank" 
                       style="display: inline-block; padding: 8px 16px; background: #E89A3C; color: white; text-decoration: none; border-radius: 5px; font-size: 13px; font-weight: 600; margin-top: 5px;">
                        Get Directions
                    </a>
                </div>
            `
        });
        
        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });
        
        // Open info window by default
        setTimeout(() => {
            infoWindow.open(map, marker);
        }, 500);
    }
    
    // Load Google Maps API
    window.initMap = initMap;
</script>

{{-- Google Maps API Script - GANTI YOUR_API_KEY dengan API Key Anda --}}
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&callback=initMap">
</script>
@endpush