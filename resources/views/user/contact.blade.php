@extends('layouts.user')

@section('title', 'Kontak Kami - Grand Azure Hotel')

@section('content')
<style>
    .contact-card {
        transition: all 0.3s ease;
    }
    .contact-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    .contact-icon {
        transition: all 0.3s ease;
    }
    .contact-card:hover .contact-icon {
        transform: scale(1.1);
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }
    .form-input:focus {
        outline: none;
        border-color: #d4a574;
        box-shadow: 0 0 0 3px rgba(212, 165, 116, 0.1);
    }
    .submit-btn {
        transition: all 0.3s ease;
    }
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(212, 165, 116, 0.3);
    }
    .map-container {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
    }
    .map-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0), rgba(0,0,0,0.1));
        pointer-events: none;
        z-index: 1;
    }
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .social-icon {
        transition: all 0.3s ease;
    }
    .social-icon:hover {
        transform: translateY(-3px) scale(1.1);
    }
</style>

<!-- Hero Section -->
<div class="relative h-80 bg-cover bg-center" style="background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1200');">
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white fade-in-up px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Hubungi Kami</h1>
            <p class="text-xl md:text-2xl font-light">Kami Siap Membantu Anda 24/7</p>
            <div class="mt-6 w-24 h-1 bg-yellow-600 mx-auto"></div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <!-- Info Kontak Cards -->
    <section class="mb-16">
        <div class="grid md:grid-cols-3 gap-8">
            <!-- Alamat -->
            <div class="contact-card bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="contact-icon w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Alamat Kami</h3>
                <p class="text-gray-600 leading-relaxed">
                    Jl. Merdeka No. 123<br>
                    Jakarta Pusat<br>
                    DKI Jakarta 10110<br>
                    Indonesia
                </p>
            </div>

            <!-- Telepon -->
            <div class="contact-card bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="contact-icon w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Telepon</h3>
                <p class="text-gray-600 leading-relaxed">
                    <a href="tel:+622112345678" class="hover:text-blue-600 transition-colors">(021) 1234-5678</a><br>
                    <a href="tel:+6281234567890" class="hover:text-blue-600 transition-colors">+62 812-3456-7890</a><br>
                    <span class="text-sm text-gray-500 mt-2 inline-block">Layanan 24 Jam</span>
                </p>
            </div>

            <!-- Email -->
            <div class="contact-card bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="contact-icon w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Email</h3>
                <p class="text-gray-600 leading-relaxed">
                    <a href="mailto:info@grandazure.com" class="hover:text-blue-600 transition-colors">info@grandazure.com</a><br>
                    <a href="mailto:reservation@grandazure.com" class="hover:text-blue-600 transition-colors">reservation@grandazure.com</a><br>
                    <span class="text-sm text-gray-500 mt-2 inline-block">Respon dalam 24 jam</span>
                </p>
            </div>
        </div>
    </section>

    

            <!-- Map & Business Hours -->
            <div>
                <h2 class="text-3xl font-bold mb-6 text-gray-800">Lokasi Kami</h2>
                
                <!-- Google Map Embed -->
                <div class="map-container mb-8 shadow-lg">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.666407086258!2d106.82493631476894!3d-6.175392295528822!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f5d2e764b12d%3A0x3d2ad6e1e0e9bcc8!2sMonas!5e0!3m2!1sid!2sid!4v1234567890123!5m2!1sid!2sid"
                        width="100%" 
                        height="350" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="rounded-lg">
                    </iframe>
                </div>

                <!-- Business Hours -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Jam Operasional
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700 font-medium">Front Desk</span>
                            <span class="text-gray-600">24 Jam</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700 font-medium">Restoran</span>
                            <span class="text-gray-600">06:00 - 23:00</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700 font-medium">Spa & Fitness</span>
                            <span class="text-gray-600">07:00 - 22:00</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700 font-medium">Kolam Renang</span>
                            <span class="text-gray-600">06:00 - 20:00</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700 font-medium">Business Center</span>
                            <span class="text-gray-600">08:00 - 20:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media & FAQ -->
    <section class="mb-16">
        <div class="grid md:grid-cols-2 gap-12">
            <!-- Social Media -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-700 p-8 rounded-lg shadow-lg text-white">
                <h3 class="text-2xl font-bold mb-6">Ikuti Kami</h3>
                <p class="mb-6 opacity-90">Dapatkan update terbaru, promo spesial, dan inspirasi liburan dari Grand Azure Hotel</p>
                <div class="flex space-x-4">
                    <a href="#" class="social-icon w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-icon w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-icon w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="social-icon w-12 h-12 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick FAQ -->
            <div class="bg-white p-8 rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold mb-6 text-gray-800">Pertanyaan Umum</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-1">Bagaimana cara reservasi?</h4>
                        <p class="text-gray-600 text-sm">Anda bisa reservasi melalui website, telepon, atau email kami.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-1">Apakah tersedia airport shuttle?</h4>
                        <p class="text-gray-600 text-sm">Ya, kami menyediakan layanan antar-jemput bandara dengan biaya tambahan.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-1">Check-in dan check-out jam berapa?</h4>
                        <p class="text-gray-600 text-sm">Check-in: 14:00 | Check-out: 12:00</p>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-1">Apakah pet-friendly?</h4>
                        <p class="text-gray-600 text-sm">Maaf, untuk saat ini kami belum menerima hewan peliharaan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Emergency Contact -->
    <section class="bg-red-50 border-l-4 border-red-600 p-6 rounded-lg">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-red-600 mr-3 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <h3 class="text-lg font-bold text-red-800 mb-2">Kontak Darurat</h3>
                <p class="text-red-700">Untuk keperluan darurat, hubungi Security 24 Jam: <a href="tel:+622112349999" class="font-bold underline">(021) 1234-9999</a></p>
            </div>
        </div>
    </section>
</div>
@endsection