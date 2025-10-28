<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Septa Classic Motor">
                <span class="logo-text">Septa Classic Motor</span>
            </div>
            
            <nav class="nav">
                <a href="{{ route('home') }}" class="nav-link active">Home</a>
                <a href="{{ route('catalog') }}" class="nav-link">Catalog</a>
                <a href="{{ route('about') }}" class="nav-link">About</a>
                <a href="{{ route('order-status') }}" class="nav-link">Order Status</a>
            </nav>
            
            <div class="header-actions">
                <div class="search-box">
                    <input type="text" placeholder="Search for products...">
                    <button class="search-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
                <a href="#" class="icon-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </a>
                <a href="#" class="icon-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
            </div>
            
            <button class="mobile-menu-btn">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>