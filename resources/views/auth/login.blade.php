<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Warehouse AMTPM System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1E40AF 0%, #E60012 100%);
            min-height: 100vh;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .warehouse-illustration {
            background: url('https://via.placeholder.com/600x400/E5E7EB/6B7280?text=Warehouse+Illustration') center/cover;
            position: relative;
            overflow: hidden;
        }
        
        .warehouse-illustration::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(230, 0, 18, 0.8), rgba(30, 64, 175, 0.8));
        }
        
        .floating-icon {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group input:focus + .input-icon {
            color: #E60012;
        }
        
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6B7280;
            transition: color 0.3s ease;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #E60012, #FF6B00);
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(230, 0, 18, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    
    <div class="w-full max-w-6xl">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            
            <!-- Left Side - Login Form -->
            <div class="order-2 lg:order-1">
                <div class="login-card rounded-2xl p-8">
                    
                    <!-- Logo and Title -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 honda-red rounded-2xl flex items-center justify-center mx-auto mb-4 floating-icon">
                            <i class="bi bi-gear-fill text-white text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">Welcome Back</h1>
                        <p class="text-gray-600">Sign in to AMTPM Warehouse System</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="bi bi-check-circle-fill text-green-600 mr-3"></i>
                                <p class="text-sm text-green-800">{{ session('status') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf
                        
                        <!-- NRP Input -->
                        <div>
                            <label for="nrp" class="block text-sm font-medium text-gray-700 mb-2">
                                NRP (Nomor Register Personil)
                            </label>
                            <div class="input-group">
                                <i class="bi bi-person input-icon"></i>
                                <input id="nrp" 
                                       name="nrp" 
                                       type="text" 
                                       autocomplete="username"
                                       required
                                       value="{{ old('nrp') }}"
                                       autofocus
                                       placeholder="Enter your NRP"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent text-gray-900">
                            </div>
                            @error('nrp')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>
                            <div class="input-group">
                                <i class="bi bi-lock input-icon"></i>
                                <input id="password" 
                                       name="password" 
                                       type="password" 
                                       autocomplete="current-password"
                                       required
                                       placeholder="Enter your password"
                                       class="w-full pl-12 pr-12 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-honda-red focus:border-transparent text-gray-900">
                                <button type="button" 
                                        onclick="togglePassword()"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i id="passwordToggle" class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input name="remember" 
                                       type="checkbox" 
                                       class="w-4 h-4 text-honda-red border-gray-300 rounded focus:ring-honda-red"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">Remember me</span>
                            </label>
                            
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" 
                                   class="text-sm text-honda-red hover:underline">
                                    Forgot password?
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="btn-login w-full py-3 text-white font-semibold rounded-lg">
                            <i class="bi bi-box-arrow-in-right mr-2"></i>
                            Sign In
                        </button>
                    </form>

                    <!-- System Info -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="text-center text-xs text-gray-500">
                            <p class="mb-2">Warehouse AMTPM System v2.0</p>
                            <div class="flex items-center justify-center space-x-4">
                                <span class="flex items-center">
                                    <i class="bi bi-shield-check text-green-500 mr-1"></i>
                                    Secure Login
                                </span>
                                <span class="flex items-center">
                                    <i class="bi bi-clock text-blue-500 mr-1"></i>
                                    {{ now()->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Illustration -->
            <div class="order-1 lg:order-2 hidden lg:block">
                <div class="warehouse-illustration rounded-2xl h-96 lg:h-full min-h-[500px] relative">
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-white p-8">
                        <div class="text-center">
                            <i class="bi bi-building text-6xl mb-4 opacity-80"></i>
                            <h2 class="text-3xl font-bold mb-4">Warehouse Management System</h2>
                            <p class="text-lg opacity-90 mb-6">Advanced Maintenance & Parts Management</p>
                            
                            <div class="grid grid-cols-2 gap-4 text-left">
                                <div class="flex items-center space-x-3">
                                    <i class="bi bi-truck text-2xl"></i>
                                    <span>Unit Tracking</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <i class="bi bi-wrench text-2xl"></i>
                                    <span>Maintenance</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <i class="bi bi-qr-code text-2xl"></i>
                                    <span>QR Scanning</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <i class="bi bi-graph-up text-2xl"></i>
                                    <span>Analytics</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
        
        // Auto-focus on NRP input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nrp').focus();
        });
    </script>
</body>
</html>
