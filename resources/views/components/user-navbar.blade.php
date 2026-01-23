<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Azure - Navbar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Inter", sans-serif;
        }

        /* Navbar Container */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        /* Logo */
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-logo svg {
            width: 28px;
            height: 28px;
            color: #c9a24d;
        }

        .navbar-logo span {
            font-size: 1.35rem;
            font-weight: 600;
            color: #2c3e50;
            letter-spacing: 0.5px;
        }

        /* Desktop Menu */
        .navbar-menu {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }

        .navbar-menu a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            position: relative;
            padding: 6px 0;
            transition: color 0.3s ease;
        }

        .navbar-menu a:hover,
        .navbar-menu a.active {
            color: #c9a24d;
        }

        .navbar-menu a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -2px;
            width: 0;
            height: 2px;
            background-color: #c9a24d;
            transition: width 0.3s ease;
        }

        .navbar-menu a:hover::after,
        .navbar-menu a.active::after {
            width: 100%;
        }

        /* Right Side Buttons */
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-masuk {
            color: #2d2d2d;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
        }

        .btn-masuk:hover {
            color: #c9a24d;
        }

        .btn-daftar {
            background-color: #c9a24d;
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-daftar:hover {
            background-color: #b08b3c;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(201, 162, 77, 0.3);
        }

        /* User Dropdown */
        .user-dropdown {
            position: relative;
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            background: none;
            border: none;
            color: #2d2d2d;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .user-dropdown-btn:hover {
            background-color: #faf8f3;
            color: #c9a24d;
        }

        .user-dropdown-btn svg {
            flex-shrink: 0;
        }

        .user-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            overflow: hidden;
            display: none;
            z-index: 1000;
        }

        .user-dropdown-menu.active {
            display: block;
        }

        .user-dropdown-menu a,
        .user-dropdown-menu button {
            display: block;
            width: 100%;
            padding: 12px 16px;
            color: #2d2d2d;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: none;
            background: none;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }

        .user-dropdown-menu a:hover,
        .user-dropdown-menu button:hover {
            background-color: #faf8f3;
            color: #c9a24d;
        }

        .dropdown-logout {
            border-top: 1px solid #e5e7eb;
        }

        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }

        .mobile-menu-toggle span {
            display: block;
            width: 25px;
            height: 3px;
            background-color: #2d2d2d;
            margin: 5px 0;
            transition: 0.3s;
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            background-color: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 1rem 0;
        }

        .mobile-menu.active {
            display: block;
        }

        .mobile-menu a {
            display: block;
            padding: 12px 1.5rem;
            color: #2d2d2d;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .mobile-menu a:hover,
        .mobile-menu a.active {
            background-color: #faf8f3;
            color: #c9a24d;
            border-left: 3px solid #c9a24d;
        }

        .mobile-menu .btn-daftar {
            margin: 8px 1.5rem;
            text-align: center;
            border-radius: 8px;
        }

        .navbar-logo {
            max-height: 40px;
            height: auto;
            width: auto;
            display: flex;
            align-items: center;
            gap: 8px;

        }

        /* Responsive */
        @media (max-width: 768px) {

            .navbar-menu,
            .navbar-actions {
                display: none;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .navbar-container {
                padding: 0 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="navbar-logo">
                <img
                    src="{{ asset('assets/images/grand-azure-logo-gold.png') }}"
                    alt="Grand Azure Hotel"
                    class="navbar-logo" />
                <span>Grand Azure</span>
            </a>

            <!-- Desktop Menu -->
            <ul class="navbar-menu">
                <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                </li>
                <li><a href="{{ route('rooms.index') }}"
                        class="{{ request()->routeIs('rooms.*') ? 'active' : '' }}">Kamar</a></li>
                <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang
                        Kami</a></li>
                <li><a href="{{ route('contact') }}"
                        class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a></li>
                @auth
                <li><a href="{{ route('reservations.index') }}"
                        class="{{ request()->routeIs('reservations.*') ? 'active' : '' }}">Reservasi Saya</a></li>
                @endauth
            </ul>

            <!-- Desktop Actions -->
            <div class="navbar-actions">
                @guest
                <a href="{{ route('login') }}" class="btn-masuk">Masuk</a>
                <a href="{{ route('register') }}" class="btn-daftar">Daftar</a>
                @else
                <div class="user-dropdown">
                    <button class="user-dropdown-btn" id="userDropdownBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>{{ Auth::user()->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="user-dropdown-menu" id="userDropdownMenu">
                        <a href="{{ route('profile.index') }}">Profil</a>
                        <a href="{{ route('reservations.index') }}">Reservasi Saya</a>
                        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="dropdown-logout">Keluar</button>
                        </form>
                    </div>
                </div>
                @endguest
            </div>

            <!-- Mobile Toggle -->
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobileMenu">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('rooms.index') }}" class="{{ request()->routeIs('rooms.*') ? 'active' : '' }}">Kamar</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Tentang Kami</a>
            <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
            @auth
            <a href="{{ route('reservations.index') }}"
                class="{{ request()->routeIs('reservations.*') ? 'active' : '' }}">Reservasi Saya</a>
            <a href="{{ route('profile.index') }}">Profil</a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0; padding: 0;">
                @csrf
                <button type="submit"
                    style="width: 100%; text-align: left; padding: 12px 1.5rem; background: none; border: none; color: #2d2d2d; font-size: 1rem; cursor: pointer; border-top: 1px solid #e5e7eb;">Keluar</button>
            </form>
            @else
            <a href="{{ route('login') }}">Masuk</a>
            <a href="{{ route('register') }}" class="btn-daftar">Daftar</a>
            @endguest
        </div>
    </nav>

    <script>
        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
        });

        // User Dropdown Toggle
        const userDropdownBtn = document.getElementById('userDropdownBtn');
        const userDropdownMenu = document.getElementById('userDropdownMenu');

        if (userDropdownBtn) {
            userDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdownMenu.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userDropdownBtn.contains(e.target)) {
                    userDropdownMenu.classList.remove('active');
                }
            });
        }
    </script>
</body>

</html>