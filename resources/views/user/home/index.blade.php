@extends('layouts.user')

@section('title', 'Beranda - Grand Azure Hotel')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/user/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/user/rooms.css') }}">

    <style>
        @font-face {
            font-family: "Black Mango";
            src: url("{{ asset('assets/fonts/BlackMango/BlackMango-Regular.ttf') }}") format('truetype');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }

        /* Override hero section dengan background image */
        .hero-section {
            background-image: url("{{ asset('assets/images/grand-azure.jpg') }}");
        }

        /* Override hero title dengan Black Mango font */
        .hero-title {
            font-family: "Black Mango", "Playfair Display", serif;
            font-weight: 400;
            line-height: 1.15;
            letter-spacing: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Animated particles overlay */
        .hero-particles {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 3;
            pointer-events: none;
            opacity: 0;
            transition: opacity 1s ease-out 0.8s;
        }

        .hero-section.loaded .hero-particles {
            opacity: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float-particle 20s ease-in-out infinite;
        }

        @keyframes float-particle {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(50px);
                opacity: 0;
            }
        }

        /* Loading spinner */
        .hero-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 5;
            opacity: 1;
            transition: opacity 0.5s ease-out;
        }

        .hero-section.loaded .hero-loading {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-top-color: #c9a961;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Stagger animation untuk room cards */
        .room-card {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .room-card.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Form input focus effects */
        .checkin-box input:focus,
        .checkin-box select:focus {
            animation: input-glow 0.3s ease-out;
        }

        @keyframes input-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(201, 169, 97, 0.4);
            }
            100% {
                box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.1);
            }
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
    </style>
@endpush

@section('content')

    <!-- HERO + CHECKIN BACKGROUND -->
    <section class="hero-section" id="hero">
        
        <!-- Loading Indicator -->
        <div class="hero-loading">
            <div class="spinner"></div>
        </div>

        <!-- Animated Particles -->
        <div class="hero-particles" id="particles"></div>

        <div class="hero-content">
            <h1 class="hero-title">
                {{ $hotelProfile->hero_title ?? 'Pengalaman Menginap Mewah' }}
            </h1>
            <p class="hero-subtitle">
                {{ $hotelProfile->hero_subtitle ?? 'Temukan kenyamanan sempurna di setiap sudut' }}
            </p>

            <!-- CHECKIN BOX -->
            <div class="checkin-box">
                <form action="{{ route('rooms.index') }}" method="GET" class="grid md:grid-cols-4 gap-4" id="searchForm">
                    <div>
                        <label>Check-in</label>
                        <input type="date" name="check_in" id="checkIn" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div>
                        <label>Check-out</label>
                        <input type="date" name="check_out" id="checkOut" value="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>

                    <div>
                        <label>Tamu</label>
                        <select name="guests" id="guests">
                            <option value="1">1 Tamu</option>
                            <option value="2" selected>2 Tamu</option>
                            <option value="3">3 Tamu</option>
                            <option value="4">4 Tamu</option>
                        </select>
                    </div>

                    <div>
                        <label>Kamar</label>
                        <select name="rooms" id="rooms">
                            <option value="1" selected>1 Kamar</option>
                            <option value="2">2 Kamar</option>
                            <option value="3">3 Kamar</option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <button type="submit" class="btn-primary w-full" id="searchBtn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="search-icon">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            <span class="btn-text">Cari Kamar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- POPULAR ROOMS -->
    <section class="py-20 bg-white" id="rooms">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="section-title">Kamar Populer</h2>
            <p class="section-subtitle">Pilihan terbaik untuk kenyamanan Anda</p>

            <div class="rooms-grid" style="margin-top: 2rem;" id="roomsGrid">
                @forelse($popularRooms as $index => $room)
                    <div class="room-card" data-index="{{ $index }}" style="transition-delay: {{ $index * 0.1 }}s">
                        <x-room-card :room="$room" />
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500 text-lg">Kamar belum tersedia</p>
                    </div>
                @endforelse
            </div>

            @if ($popularRooms->isNotEmpty())
                <div class="view-all-section">
                    <a href="{{ route('rooms.index') }}" class="btn-view-all" id="viewAllBtn">
                        Lihat Semua Kamar
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             style="display: inline-block; margin-left: 8px; transition: transform 0.3s ease;">
                            <path d="M5 12h14"></path>
                            <path d="m12 5 7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </section>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/user/home.js') }}"></script>
    
    <script>
        // Immediate execution untuk smooth experience
        (function() {
            // Create floating particles
            const particlesContainer = document.getElementById('particles');
            if (particlesContainer) {
                for (let i = 0; i < 15; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 20 + 's';
                    particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                    particlesContainer.appendChild(particle);
                }
            }

            // Enhanced view all button hover effect
            const viewAllBtn = document.getElementById('viewAllBtn');
            if (viewAllBtn) {
                const arrow = viewAllBtn.querySelector('svg');
                viewAllBtn.addEventListener('mouseenter', function() {
                    if (arrow) {
                        arrow.style.transform = 'translateX(5px)';
                    }
                });
                viewAllBtn.addEventListener('mouseleave', function() {
                    if (arrow) {
                        arrow.style.transform = 'translateX(0)';
                    }
                });
            }

            // Intersection Observer untuk room cards
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all room cards
            document.querySelectorAll('.room-card').forEach(card => {
                observer.observe(card);
            });

            // Add smooth scroll to rooms section
            const heroSection = document.getElementById('hero');
            if (heroSection && window.innerHeight) {
                let lastScroll = 0;
                window.addEventListener('scroll', function() {
                    const currentScroll = window.pageYOffset;
                    if (currentScroll > lastScroll && currentScroll > 100) {
                        // Scrolling down
                        document.body.classList.add('scrolled');
                    } else {
                        document.body.classList.remove('scrolled');
                    }
                    lastScroll = currentScroll;
                }, { passive: true });
            }
        })();
    </script>
@endpush