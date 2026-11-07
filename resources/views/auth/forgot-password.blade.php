<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-favicon />
    <title>Forgot Password - Warehouse AMTPM System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1E40AF 0%, #E60012 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .forgot-container {
            width: 100%;
            max-width: 500px;
        }
        
        .forgot-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #1E40AF, #E60012);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .logo-icon i {
            font-size: 2.5rem;
            color: white;
        }
        
        .card-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        
        .card-subtitle {
            color: #64748b;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.25rem;
            transition: color 0.3s ease;
        }
        
        .form-input {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #E60012;
            box-shadow: 0 0 0 3px rgba(230, 0, 18, 0.1);
        }
        
        .form-input:focus + .input-icon {
            color: #E60012;
        }
        
        .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #E60012, #FF6B00);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(230, 0, 18, 0.3);
        }
        
        .back-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .back-link a {
            color: #E60012;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link a:hover {
            color: #1E40AF;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .forgot-card {
                padding: 2rem;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <!-- Logo Section -->
            <div class="logo-section">
                @php $authLogo = \App\Models\AppLogo::getActiveLogo('default'); @endphp
                @if($authLogo && $authLogo->image_path)
                    <img src="{{ $authLogo->image_url }}" alt="{{ $authLogo->alt_text ?? config('app.name') }}" style="display: block; width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1.5rem;">
                @else
                    <div class="logo-icon">
                        <i class="bi bi-key-fill"></i>
                    </div>
                @endif
                <h1 class="card-title">{{ trans('messages.forgot_password') }}</h1>
                <p class="card-subtitle">{{ trans('messages.forgot_password_instruction') }}</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <!-- NRP -->
                <div class="form-group">
                    <label for="nrp" class="form-label">{{ trans('messages.nrp') }}</label>
                    <div class="input-wrapper">
                        <i class="bi bi-credit-card input-icon"></i>
                        <input id="nrp"
                               name="nrp"
                               type="text"
                               autocomplete="username"
                               required
                               value="{{ old('nrp') }}"
                               autofocus
                               placeholder="{{ trans('messages.enter_nrp') }}"
                               class="form-input">
                    </div>
                    @error('nrp')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Send Reset Link Button -->
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send"></i>
                    {{ trans('messages.send_reset_link') }}
                </button>
            </form>

            <!-- Back to Login Link -->
            <div class="back-link">
                <a href="{{ route('login') }}">
                    <i class="bi bi-arrow-left"></i>
                    {{ trans('messages.sign_in_link') }}
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus on NRP input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nrp').focus();
        });
    </script>
</body>
</html>
