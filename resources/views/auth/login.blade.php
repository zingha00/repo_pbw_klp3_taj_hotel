<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hotel Booking</title>
    <link rel="stylesheet" href="{{ asset('css/login.page.css') }}">
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>

</head>

<body>
    <!-- Back Button -->
    <a href="{{ route('home') }}" class="btn-back" title="Back to Home">
        <i data-feather="arrow-left"></i>
    </a>

    <div class="auth-page">
        <div class="auth-left">
            <div class="auth-left-content">
                <h1>Welcome Back!</h1>
                <p>Sign in to access your hotel bookings and manage your reservations with ease.</p>
            </div>
        </div>

        <div class="auth-right">
            <div class="auth-box">
                <div class="auth-header">
                    <div class="logo-big">
                    <img src="{{ asset('img/images.jpg') }}" alt="Hotel Logo">
                    </div>
                    <h2>Sign In</h2>
                    <p>Enter your credentials to access your account</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="auth-form">
                    @csrf

                    <!-- Role Selector -->
                    <div class="form-group">
                        <label>Login as:</label>
                        <div class="role-selector">
                            <div class="role-option">
                                <input type="radio" name="role" value="customer" id="role-customer" checked>
                                <label for="role-customer" class="role-card">
                                    <div class="role-card-icon">
                                        <i data-feather="user"></i>
                                    </div>
                                    <h4>Customer</h4>
                                    <p>Book rooms & manage reservations</p>
                                </label>
                            </div>
                            <div class="role-option">
                                <input type="radio" name="role" value="admin" id="role-admin">
                                <label for="role-admin" class="role-card">
                                    <div class="role-card-icon">
                                        <i data-feather="shield"></i>
                                    </div>
                                    <h4>Admin</h4>
                                    <p>Manage hotel & dashboard</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-with-icon">
                            <i data-feather="mail" class="input-icon"></i>
                            <input type="email" id="email" name="email" placeholder="your.email@example.com" required
                                value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>

                        <div class="password-field">
                            <i data-feather="lock" class="input-icon"></i>

                            <input type="password" id="password" name="password" placeholder="Enter your password"
                                required>

                            <span class="toggle-password" onclick="togglePassword()">
                                <i data-feather="eye" id="eye-icon"></i>
                            </span>
                        </div>

                        @error('password')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Quick Login Hint -->
                    <div class="quick-login-hint">
                        <small style="color: #999;">
                            <i data-feather="info" style="width: 14px; height: 14px;"></i>
                            <strong>Quick Login:</strong><br>
                            Admin: admin@hotel.com / password<br>
                            User: user@example.com / password
                        </small>
                    </div>

                    <button type="submit" class="btn-login">
                        <i data-feather="log-in" style="width: 18px; height: 18px;"></i>
                        Sign In
                    </button>
                </form>

                <div class="divider">
                    <span>OR</span>
                </div>

                <div class="social-login">
                    <a href="{{ route('auth.google') }}" class="btn-social">
                        <svg width="18" height="18" viewBox="0 0 18 18">
                            <path fill="#4285F4"
                                d="M16.51 8H8.98v3h4.3c-.18 1-.74 1.48-1.6 2.04v2.01h2.6a7.8 7.8 0 0 0 2.38-5.88c0-.57-.05-.66-.15-1.18z" />
                            <path fill="#34A853"
                                d="M8.98 17c2.16 0 3.97-.72 5.3-1.94l-2.6-2a4.8 4.8 0 0 1-7.18-2.54H1.83v2.07A8 8 0 0 0 8.98 17z" />
                            <path fill="#FBBC05"
                                d="M4.5 10.52a4.8 4.8 0 0 1 0-3.04V5.41H1.83a8 8 0 0 0 0 7.18l2.67-2.07z" />
                            <path fill="#EA4335"
                                d="M8.98 4.18c1.17 0 2.23.4 3.06 1.2l2.3-2.3A8 8 0 0 0 1.83 5.4L4.5 7.49a4.77 4.77 0 0 1 4.48-3.3z" />
                        </svg>
                        Google
                    </a>
                </div>

                <div class="auth-footer">
                    Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather Icons
        feather.replace();

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.setAttribute('data-feather', 'eye-off');
            } else {
                passwordInput.type = 'password';
                eyeIcon.setAttribute('data-feather', 'eye');
            }
            feather.replace();
        }
    </script>
</body>

</html>