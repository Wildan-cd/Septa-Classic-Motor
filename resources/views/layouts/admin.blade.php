<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Septa Classic Motor</title>
    
    {{-- Admin CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    {{-- Additional CSS --}}
    @stack('styles')
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    {{-- Chart.js for graphs --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">
    @include('partials.admin-header')
    
    <main class="admin-main">
        @yield('content')
    </main>
    
    {{-- Admin JavaScript --}}
    <script src="{{ asset('js/admin.js') }}"></script>
    
    {{-- Additional Scripts --}}
    @stack('scripts')
</body>
</html>