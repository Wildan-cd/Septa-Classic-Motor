<header class="admin-header">
    <div class="admin-header-container">
        <div class="admin-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Septa Classic Motor">
            <span class="admin-logo-text">Septa Classic Motor</span>
        </div>
        
        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.catalog.index') }}" class="admin-nav-link {{ request()->routeIs('admin.catalog.index') ? 'active' : '' }}">
                Catalog
            </a>
            <a href="{{ route('admin.orders') }}" class="admin-nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                Order List
            </a>
        </nav>
        
        <div class="admin-user">
            <div class="admin-user-info">
                <span class="admin-user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
                <span class="admin-user-role">{{ ucfirst(Auth::user()->role ?? 'admin') }}</span>
            </div>
            <div class="admin-user-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="admin-dropdown">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item logout">Logout</button>
                </form>
            </div>
        </div>
    </div>
</header>