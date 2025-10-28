<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Septa Classic Motor')</title>
    
    {{-- Main CSS --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    {{-- Additional CSS for specific pages --}}
    @stack('styles')
    
    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
</head>
<body>
    @include('partials.header')
    
    <main>
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    {{-- Main JavaScript --}}
    <script src="{{ asset('js/script.js') }}"></script>
    
    {{-- Additional Scripts for specific pages --}}
    @stack('scripts')
</body>
</html>