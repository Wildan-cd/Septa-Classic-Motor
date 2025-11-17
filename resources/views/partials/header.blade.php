<header class="header">
    <div class="container">
        <div class="header-content">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="Septa Classic Motor">
                <span class="logo-text">Septa Classic Motor</span>
            </a>
            
            <nav class="nav" id="mainNav">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    Home
                </a>
                <a href="{{ route('catalog') }}" class="nav-link {{ request()->routeIs('catalog') || request()->routeIs('product.detail') ? 'active' : '' }}">
                    Catalog
                </a>
                <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">
                    About
                </a>
                <a href="{{ route('order-status') }}" class="nav-link {{ request()->routeIs('order-status') ? 'active' : '' }}">
                    Order Status
                </a>
            </nav>
            
            <div class="header-actions">
                <div class="search-box">
                    <input type="text" placeholder="Search for products..." id="searchInput">
                    <button class="search-btn" type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </div>
                
                <a href="{{ route('cart.index') }}" class="icon-btn cart-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </a>
                
                @auth
                {{-- User Dropdown (when logged in) --}}
                <div class="user-dropdown-container">
                    <div class="user-trigger">
                        <div class="user-info">
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'Customer') }}</span>
                        </div>
                        <div class="user-avatar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                    </div>
                    <div class="user-dropdown">
                        <div class="dropdown-header">
                            <div class="dropdown-avatar">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="dropdown-user-info">
                                <div class="dropdown-name">{{ Auth::user()->name }}</div>
                                <div class="dropdown-email">{{ Auth::user()->email }}</div>
                            </div>
                        </div>
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                        <hr class="dropdown-divider">
                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                            Dashboard
                        </a>
                        @endif
                        <hr class="dropdown-divider">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item logout">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
                @else
                {{-- Login Button (when not logged in) --}}
                <a href="{{ route('login') }}" class="icon-btn user-btn" title="Login">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
                @endauth
            </div>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>