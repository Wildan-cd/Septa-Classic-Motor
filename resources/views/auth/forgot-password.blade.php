<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Septa Classic Motor</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Left Side - Image with Logo -->
            <div class="auth-left">
                <div class="auth-image-overlay"></div>
                <div class="auth-logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="Septa Classic Motor" class="auth-logo">
                    <h2 class="auth-brand">Septa Classic Motor</h2>
                    <p class="auth-tagline">We'll help you recover your account</p>
                </div>
            </div>
            
            <!-- Right Side - Forgot Password Form -->
            <div class="auth-right">
                <div class="auth-form-container">
                    <h1 class="auth-title">Forgot Password?</h1>
                    <p class="auth-subtitle">Enter your email address and we'll send you a link to reset your password</p>
                    
                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    
                    <!-- Success Message -->
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    
                    <form action="{{ route('password.email') }}" method="POST" class="auth-form">
                        @csrf
                        
                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                placeholder="Enter your registered email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                            @error('email')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn-login">Send Reset Link</button>
                    </form>
                    
                    <!-- Back to Login Link -->
                    <div class="auth-footer">
                        <a href="{{ route('login') }}" class="back-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 12H5M12 19l-7-7 7-7"/>
                            </svg>
                            Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>