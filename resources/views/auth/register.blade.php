@extends('layouts.user')

@section('title', 'Daftar - Grand Azure Hotel')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth/register.css') }}">
@endpush

@section('content')
    <section class="register-section">
        <div class="register-container">
            <div class="auth-back-wrapper">
                <a href="{{ url('/') }}" class="auth-back-button">
                    <i data-feather="arrow-left"></i>
                    <span>Kembali</span>
                </a>
            </div>

            <div class="register-card">
                <!-- Header -->
                <div class="register-header">
                    <img
                    src="{{ asset('assets/images/grand-azure-logo-gold.png') }}"
                    alt="Grand Azure Hotel"
                    class="register-logo" />
                    <h1 class="register-title">Buat Akun</h1>
                    <p class="register-subtitle">Daftar untuk memesan kamar hotel</p>
                </div>

                <!-- Alert Messages -->
                @if($errors->any())
                    <div class="register-alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="register-alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Registration Form -->
                <form action="{{ route('register') }}" method="POST" class="register-form" id="register-form">
                    @csrf

                    <!-- Name Field -->
                    <div class="form-group">
                        <label class="form-label" for="name">
                            Nama Lengkap <span class="required">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-input @error('name') error @enderror" placeholder="Masukkan nama lengkap Anda"
                            required>
                        @error('name')
                            <div class="error-message">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            Email <span class="required">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-input @error('email') error @enderror" placeholder="nama@email.com" required>
                        @error('email')
                            <div class="error-message">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group">
                        <label class="form-label" for="phone">
                            Nomor Telepon <span class="required">*</span>
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="form-input @error('phone') error @enderror" placeholder="08xxxxxxxxxx" required>

                        @error('phone')
                            <div class="error-message">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label class="form-label" for="password">
                            Password <span class="required">*</span>
                        </label>

                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-input"
                                placeholder="Minimal 8 karakter" required>

                            <button type="button" class="password-toggle" data-target="password"
                                aria-label="Toggle password">
                                <i data-feather="eye"></i>
                            </button>
                        </div>

                        <!-- Password Strength Indicator -->
                        <div class="password-strength" id="password-strength" style="display: none;">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <div class="strength-text" id="strength-text"></div>
                        </div>

                        @error('password')
                            <div class="error-message">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>


                    <!-- Password Confirmation Field -->
                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">
                            Konfirmasi Password <span class="required">*</span>
                        </label>

                        <div class="input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-input" placeholder="Ulangi password" required>
                            <button type="button" class="password-toggle" data-target="password_confirmation"
                                aria-label="Toggle password confirmation">
                                <i data-feather="eye"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Terms and Conditions -->
                    <div class="terms-checkbox">
                        <input type="checkbox" name="terms" id="terms" required>
                        <label for="terms">
                            Saya setuju dengan <a href="#" onclick="return false;">Syarat & Ketentuan</a>
                            dan <a href="#" onclick="return false;">Kebijakan Privasi</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="register-button" id="submit-btn">
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="login-wrapper">
                    <p class="login-text">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="login-link">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="{{ asset('assets/js/auth/register.js') }}"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/auth/register.js') }}"></script>
@endpush