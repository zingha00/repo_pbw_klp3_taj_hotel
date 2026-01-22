@extends('layouts.user')

@section('title', 'Lupa Password - Grand Azure Hotel')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth/forgot-password.css') }}">
@endpush

@section('content')
    <section class="auth-section">
        <div class="auth-container">
            <div class="auth-card">

                <!-- Header -->
                <div class="auth-header">
                    <div class="auth-icon-wrapper">
                        <svg class="auth-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>

                    <h1 class="auth-title">Lupa Password</h1>
                    <p class="auth-subtitle">Masukkan email untuk reset password</p>
                </div>

                <!-- Alerts -->
                @if(session('status'))
                    <div class="auth-alert success">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="auth-alert error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('password.email') }}" method="POST" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-input"
                            placeholder="email@example.com">
                    </div>

                    <button type="submit" class="auth-button">
                        Kirim Link Reset
                    </button>
                </form>

                <!-- Footer -->
                <div class="auth-footer">
                    <a href="{{ route('login') }}" class="auth-link">
                        Kembali ke Login
                    </a>
                </div>

            </div>
        </div>
    </section>
@endsection