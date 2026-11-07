<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-favicon />
    <title>Register - Warehouse AMTPM System</title>
    
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
        
        .register-container {
            width: 100%;
            max-width: 500px;
        }
        
        .register-card {
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
        }
        
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
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
        
        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.25rem;
            transition: color 0.3s ease;
        }
        
        .password-toggle:hover {
            color: #1e293b;
        }
        
        .checkbox-group {
            margin-bottom: 1.5rem;
        }
        
        .checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        
        .checkbox {
            width: 1.25rem;
            height: 1.25rem;
            accent-color: #E60012;
            cursor: pointer;
            margin-top: 0.125rem;
        }
        
        .checkbox-label {
            font-size: 0.875rem;
            color: #64748b;
            line-height: 1.5;
            cursor: pointer;
        }
        
        .checkbox-label a {
            color: #E60012;
            text-decoration: none;
            font-weight: 500;
        }
        
        .checkbox-label a:hover {
            color: #1E40AF;
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
        
        .btn-google {
            background: white;
            color: #1e293b;
            border: 2px solid #e2e8f0;
            margin-top: 1rem;
        }
        
        .btn-google:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        
        .divider {
            display: flex;
            align-items: center;
            margin: 1.5rem 0;
            color: #94a3b8;
            font-size: 0.875rem;
        }
        
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        
        .divider span {
            padding: 0 1rem;
        }
        
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            font-size: 0.875rem;
        }
        
        .login-link a {
            color: #E60012;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-link a:hover {
            color: #1E40AF;
        }
        
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        /* Responsive */
        @media (max-width: 640px) {
            .register-card {
                padding: 2rem;
            }
            
            .card-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Logo Section -->
            <div class="logo-section">
                @php $authLogo = \App\Models\AppLogo::getActiveLogo('default'); @endphp
                @if($authLogo && $authLogo->image_path)
                    <img src="{{ $authLogo->image_url }}" alt="{{ $authLogo->alt_text ?? config('app.name') }}" style="display: block; width: 80px; height: 80px; object-fit: contain; margin: 0 auto 1.5rem;">
                @else
                    <div class="logo-icon">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                @endif
                <h1 class="card-title">{{ trans('messages.create_account') }}</h1>
                <p class="card-subtitle">{{ trans('messages.join_system') }}</p>
            </div>

            <!-- Alert Messages -->
            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <span>{{ trans('messages.fix_errors') }}</span>
                </div>
            @endif

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name" class="form-label">{{ trans('messages.full_name') }}</label>
                    <div class="input-wrapper">
                        <i class="bi bi-person input-icon"></i>
                        <input id="name"
                               name="name"
                               type="text"
                               autocomplete="name"
                               required
                               value="{{ old('name') }}"
                               placeholder="{{ trans('messages.enter_full_name') }}"
                               class="form-input">
                    </div>
                    @error('name')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

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
                               placeholder="{{ trans('messages.enter_nrp') }}"
                               class="form-input">
                    </div>
                    @error('nrp')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email (Optional) -->
                <div class="form-group">
                    <label for="email" class="form-label">{{ trans('messages.email_optional') }}</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope input-icon"></i>
                        <input id="email"
                               name="email"
                               type="email"
                               autocomplete="email"
                               value="{{ old('email') }}"
                               placeholder="{{ trans('messages.enter_email') }}"
                               class="form-input">
                    </div>
                    @error('email')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">{{ trans('messages.password') }}</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input id="password"
                               name="password"
                               type="password"
                               autocomplete="new-password"
                               required
                               placeholder="{{ trans('messages.create_password') }}"
                               class="form-input">
                        <button type="button"
                                onclick="togglePassword('password', 'passwordToggle')"
                                class="password-toggle">
                            <i id="passwordToggle" class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">{{ trans('messages.confirm_password') }}</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input id="password_confirmation"
                               name="password_confirmation"
                               type="password"
                               autocomplete="new-password"
                               required
                               placeholder="{{ trans('messages.confirm_your_password') }}"
                               class="form-input">
                        <button type="button"
                                onclick="togglePassword('password_confirmation', 'passwordConfirmToggle')"
                                class="password-toggle">
                            <i id="passwordConfirmToggle" class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Terms & Privacy Policy Checkboxes -->
                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input name="terms"
                               type="checkbox"
                               id="terms"
                               class="checkbox"
                               required
                               {{ old('terms') ? 'checked' : '' }}>
                        <label for="terms" class="checkbox-label">
                            {{ trans('messages.agree_terms') }} <a href="#" onclick="showTermsModal()">{{ trans('messages.terms_conditions') }}</a>
                        </label>
                    </div>
                    @error('terms')
                        <p class="error-message">{{ $message }}</p>
                    @enderror

                    <div class="checkbox-item">
                        <input name="privacy"
                               type="checkbox"
                               id="privacy"
                               class="checkbox"
                               required
                               {{ old('privacy') ? 'checked' : '' }}>
                        <label for="privacy" class="checkbox-label">
                            {{ trans('messages.agree_privacy') }} <a href="#" onclick="showPrivacyModal()">{{ trans('messages.privacy_policy') }}</a>
                        </label>
                    </div>
                    @error('privacy')
                        <p class="error-message">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i>
                    {{ trans('messages.sign_up') }}
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>{{ trans('messages.or_register_with') }}</span>
            </div>

            <!-- Google Register Button -->
            <a href="{{ route('auth.google') }}" class="btn btn-google">
                <i class="bi bi-google"></i>
                {{ trans('messages.sign_up_with_google') }}
            </a>

            <!-- Login Link -->
            <div class="login-link">
                {{ trans('messages.already_have_account') }}
                <a href="{{ route('login') }}">{{ trans('messages.sign_in_link') }}</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);
            
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
        
        function showTermsModal() {
            alert('Terms and Conditions:\n\n1. By using this system, you agree to comply with all company policies.\n2. You are responsible for maintaining the confidentiality of your account credentials.\n3. You must not use the system for any unauthorized purposes.\n4. The company reserves the right to modify these terms at any time.');
        }
        
        function showPrivacyModal() {
            alert('Privacy Policy:\n\n1. We collect and store your personal information for system management purposes.\n2. Your data will not be shared with third parties without your consent.\n3. We implement security measures to protect your personal information.\n4. You have the right to request access to or deletion of your personal data.');
        }
        
        // Auto-focus on name input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('name').focus();
        });
    </script>
</body>
</html>
