<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Taj Hotel')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container">

            <!-- BRAND -->
            <div class="nav-brand">
                <img src="{{ asset('images/images.jpg') }}" alt="Hotel Logo" class="logo">
                <span class="brand-name">Taj-Hotel</span>
            </div>

            <!-- MENU -->
            <ul class="nav-menu">
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        HOME
                    </a>
                </li>

                <li>
                    <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                        ROOMS
                    </a>
                </li>

                <li>
                    <a href="{{ route('reservations.my') }}"
                        class="{{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                        MY RESERVATION
                    </a>
                </li>

                <li>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">
                        ABOUT
                    </a>
                </li>

                <li>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                        CONTACT
                    </a>
                </li>

                @auth
                    @if (Auth::user()->role === 'admin')
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                                class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                DASHBOARD
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- AUTH / PROFILE -->
            <div class="nav-actions">
                @auth
                    <div class="user-profile">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar }}" alt="Profile" class="profile-avatar">
                        @else
                            <div class="profile-avatar-text">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif

                        <span>{{ Auth::user()->name }}</span>

                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-logout">Sign Out</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-sign">Sign In</a>
                @endauth
            </div>

        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">

            <div class="footer-content">

                <div class="footer-col">
                    <div class="footer-brand">
                        <img src="{{ asset('images/images.jpg') }}" alt="Hotel Logo" class="logo">
                        <span class="brand-name">Taj-Hotel</span>
                    </div>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </p>
                </div>

                <div class="footer-col">
                    <h3>Links</h3>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('rooms.index') }}">Rooms</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Legal</h3>
                    <ul>
                        <li><a href="#">Legal</a></li>
                        <li><a href="#">Term & Condition</a></li>
                        <li><a href="#">Payment Method</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="footer-col">
                    <h3>Newsletter</h3>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing.</p>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Your email address">
                        <button type="submit" class="btn-orange">Subscribe</button>
                    </form>
                </div>

            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 | Kelompok 3 | All Rights Reserved</p>
            </div>

        </div>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>