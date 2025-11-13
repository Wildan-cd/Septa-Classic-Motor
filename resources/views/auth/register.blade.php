<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Septa Classic Motor</title>
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
                    <p class="auth-tagline">Join us and start your classic motorcycle journey</p>
                </div>
            </div>
            
            <!-- Right Side - Register Form -->
            <div class="auth-right">
                <div class="auth-form-container">
                    <h1 class="auth-title">Create Account</h1>
                    <p class="auth-subtitle">Fill in the information below to create your account</p>
                    
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
                    
                    <form action="{{ route('register.post') }}" method="POST" class="auth-form">
                        @csrf
                        
                        <!-- Name Field -->
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                placeholder="Enter your full name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                            >
                            @error('name')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Email Field -->
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                placeholder="Enter your email"
                                value="{{ old('email') }}"
                                required
                            >
                            @error('email')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-wrapper">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    placeholder="Enter your password"
                                    required
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                            <span class="error-message">{{ $message }}</span>
                            @enderror
                            <small class="form-hint">Minimum 8 characters</small>
                        </div>
                        
                        <!-- Password Confirmation Field -->
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="password-wrapper">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation" 
                                    class="form-control" 
                                    placeholder="Confirm your password"
                                    required
                                >
                                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                    <svg class="eye-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Terms & Conditions -->
                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="terms" id="terms" required>
                                <span>I agree to the <a href="#" class="link">Terms and Conditions</a></span>
                            </label>
                        </div>
                        
                        <!-- Submit Button -->
                        <button type="submit" class="btn-login">Register</button>
                    </form>
                    
                    <!-- Login Link -->
                    <div class="auth-footer">
                        <p>Already have an account? <a href="{{ route('login') }}" class="register-link">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
        }
    </script>
</body>
</html>