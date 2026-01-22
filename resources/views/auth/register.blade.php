<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Hotel Booking</title>

  <link rel="stylesheet" href="{{ asset('css/login.page.css') }}">


</head>
<body>

<a href="{{ route('home') }}" class="btn-back" title="Back to Home">←</a>

<div class="auth-page">
    <div class="auth-left">
        <div class="auth-left-content">
            <h1>Join Us Today!</h1>
            <p>Create your account and start booking luxurious hotel rooms.</p>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-box">
            <div class="auth-header">
                <div class="logo-big"><img src="{{ asset('img/images.jpg') }}" alt="Hotel Logo">
            </div>
                <h2>Create Account</h2>
                <p>Fill in your details to get started</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="auth-form">
                @csrf

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                        <span class="toggle-password" onclick="togglePassword('password_confirmation')">👁️</span>
                    </div>
                </div>

                <button type="submit" class="btn-register">Create Account</button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/auth-password-toggle.js') }}"></cript>
</body>
</html>