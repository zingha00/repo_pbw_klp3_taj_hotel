@extends('layouts.user')

@section('title', 'Login - Grand Azure Hotel')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}">
@endpush

@section('content')
<section class="login-section">
    <div class="auth-back-wrapper">
        <a href="{{ url('/') }}" class="auth-back-button">
            <i data-feather="arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="login-container">
        <div class="login-card">



            <!-- Header -->
            <div class="login-header">
                <img
                    src="{{ asset('assets/images/grand-azure-logo-gold.png') }}"
                    alt="Grand Azure Hotel"
                    class="login-logo" />

                <h1 class="login-title">Selamat Datang</h1>
                <p class="login-subtitle">Masuk ke akun Anda</p>
            </div>

            <!-- Form -->
            <form action="{{ route('login') }}" method="POST" class="login-form">
                @csrf

                <!-- Error Alert -->
                @if($errors->any())
                <div class="login-alert">
                    {{ $errors->first() }}
                </div>
                @endif

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input"
                        placeholder="email@example.com" autocomplete="email">
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" required class="form-input"
                        placeholder="••••••••" autocomplete="current-password">
                </div>

                <!-- Forgot Password Link -->
                <div class="forgot-password-wrapper">
                    <a href="{{ route('password.request') }}" class="forgot-password-link">
                        Lupa Password?
                    </a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="login-button">
                    Masuk
                </button>
            </form>

            <!-- Register Link -->
            <div class="register-wrapper">
                <span class="register-text">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="register-link">Daftar</a>
                </span>
            </div>
        </div>
    </div>
</section>
@endsection
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>

@push('scripts')
<script src="{{ asset('assets/js/auth/login.js') }}"></script>
@endpush