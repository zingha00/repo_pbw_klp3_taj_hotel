@extends('layouts.user')

@section('title', 'Tentang Kami - Grand Azure Hotel')

@section('content')
<style>
    .stat-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    .value-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .value-card:hover {
        border-color: #d4a574;
        transform: scale(1.05);
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(to bottom, #d4a574, transparent);
    }
    .fade-in {
        animation: fadeIn 0.8s ease-in;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .hero-overlay {
        background: linear-gradient(135deg, rgba(21, 30, 45, 0.85), rgba(212, 165, 116, 0.3));
    }
</style>

<!-- Hero Section -->
<div class="relative h-96 bg-cover bg-center" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1200');">
    <div class="hero-overlay absolute inset-0 flex items-center justify-center">
        <div class="text-center text-white fade-in px-4">
            <h1 class="text-5xl md:text-6xl font-bold mb-4">Grand Azure Hotel</h1>
            <p class="text-xl md:text-2xl font-light">Pengalaman Kemewahan yang Tak Terlupakan</p>
            <div class="mt-6 w-24 h-1 bg-yellow-600 mx-auto"></div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-16">
    <!-- Sejarah & Deskripsi -->
    <section class="mb-20 fade-in">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold mb-6 text-gray-800">Cerita Kami</h2>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Sejak didirikan pada tahun 2010, Grand Azure Hotel telah menjadi simbol keunggulan dalam industri perhotelan. Dengan lokasi strategis di Jl. Merdeka No. 123, Jakarta Pusat, kami berkomitmen memberikan pengalaman menginap yang tak terlupakan bagi setiap tamu.
                </p>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Nama "Azure" yang berarti biru langit mencerminkan visi kami untuk memberikan kebebasan, kenyamanan, dan ketenangan yang tanpa batas kepada setiap tamu yang menginap.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Dengan lebih dari 200 kamar yang dirancang dengan elegan dan fasilitas kelas dunia, kami terus berinovasi untuk memenuhi kebutuhan wisatawan modern yang menghargai kualitas dan pelayanan prima.
                </p>
            </div>
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600" alt="Grand Azure Hotel" class="rounded-lg shadow-2xl w-full h-96 object-cover">
                <div class="absolute -bottom-6 -right-6 bg-yellow-600 text-white p-6 rounded-lg shadow-xl">
                    <p class="text-4xl font-bold">14+</p>
                    <p class="text-sm">Tahun Pengalaman</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Visi & Misi -->
    <section class="mb-20 bg-gray-50 -mx-4 px-4 md:mx-0 md:px-12 py-16 rounded-lg">
        <div class="grid md:grid-cols-2 gap-12">
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Visi Kami</h3>
                </div>
                <p class="text-gray-600 leading-relaxed">
                    Menjadi hotel pilihan utama di Indonesia yang dikenal dengan pelayanan berkelas internasional, fasilitas modern, dan pengalaman menginap yang berkesan bagi setiap tamu.
                </p>
            </div>
            
            <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-600 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800">Misi Kami</h3>
                </div>
                <ul class="text-gray-600 space-y-2">
                    <li class="flex items-start">
                        <span class="text-yellow-600 mr-2">✓</span>
                        <span>Memberikan pelayanan terbaik dengan standar internasional</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-yellow-600 mr-2">✓</span>
                        <span>Menyediakan fasilitas modern dan nyaman</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-yellow-600 mr-2">✓</span>
                        <span>Menciptakan pengalaman menginap yang tak terlupakan</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-yellow-600 mr-2">✓</span>
                        <span>Berkomitmen pada keberlanjutan lingkungan</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Statistik -->
    <section class="mb-20">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Pencapaian Kami</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="stat-card bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="text-5xl font-bold text-blue-600 mb-2">200+</div>
                <p class="text-gray-600">Kamar Tersedia</p>
            </div>
            <div class="stat-card bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="text-5xl font-bold text-yellow-600 mb-2">50K+</div>
                <p class="text-gray-600">Tamu Puas</p>
            </div>
            <div class="stat-card bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="text-5xl font-bold text-green-600 mb-2">4.8</div>
                <p class="text-gray-600">Rating Rata-rata</p>
            </div>
            <div class="stat-card bg-white p-8 rounded-lg shadow-lg text-center">
                <div class="text-5xl font-bold text-purple-600 mb-2">15+</div>
                <p class="text-gray-600">Penghargaan</p>
            </div>
        </div>
    </section>

    <!-- Nilai-Nilai Perusahaan -->
    <section class="mb-20">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Nilai-Nilai Kami</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="value-card bg-white p-8 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Keramahan</h3>
                <p class="text-gray-600">Menyambut setiap tamu dengan senyuman dan pelayanan tulus dari hati</p>
            </div>
            
            <div class="value-card bg-white p-8 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Kualitas</h3>
                <p class="text-gray-600">Berkomitmen pada standar tertinggi dalam setiap aspek layanan kami</p>
            </div>
            
            <div class="value-card bg-white p-8 rounded-lg shadow-md text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">Integritas</h3>
                <p class="text-gray-600">Menjunjung tinggi kejujuran dan transparansi dalam setiap tindakan</p>
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="mb-20">
        <h2 class="text-4xl font-bold text-center mb-12 text-gray-800">Perjalanan Kami</h2>
        <div class="max-w-3xl mx-auto">
            <div class="relative pl-8 space-y-8">
                <div class="timeline-item relative">
                    <div class="absolute left-0 top-2 w-4 h-4 bg-yellow-600 rounded-full -ml-2"></div>
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <span class="text-yellow-600 font-bold">2010</span>
                        <h4 class="text-xl font-bold mt-2 mb-2 text-gray-800">Grand Opening</h4>
                        <p class="text-gray-600">Grand Azure Hotel resmi dibuka dengan 100 kamar dan menjadi landmark baru di Jakarta Pusat</p>
                    </div>
                </div>
                
                <div class="timeline-item relative">
                    <div class="absolute left-0 top-2 w-4 h-4 bg-yellow-600 rounded-full -ml-2"></div>
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <span class="text-yellow-600 font-bold">2015</span>
                        <h4 class="text-xl font-bold mt-2 mb-2 text-gray-800">Ekspansi Pertama</h4>
                        <p class="text-gray-600">Menambah 100 kamar baru dan fasilitas spa & fitness center yang modern</p>
                    </div>
                </div>
                
                <div class="timeline-item relative">
                    <div class="absolute left-0 top-2 w-4 h-4 bg-yellow-600 rounded-full -ml-2"></div>
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <span class="text-yellow-600 font-bold">2020</span>
                        <h4 class="text-xl font-bold mt-2 mb-2 text-gray-800">Penghargaan Internasional</h4>
                        <p class="text-gray-600">Menerima penghargaan "Best Boutique Hotel" dari International Hotel Awards</p>
                    </div>
                </div>
                
                <div class="timeline-item relative">
                    <div class="absolute left-0 top-2 w-4 h-4 bg-yellow-600 rounded-full -ml-2"></div>
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <span class="text-yellow-600 font-bold">2024</span>
                        <h4 class="text-xl font-bold mt-2 mb-2 text-gray-800">Transformasi Digital</h4>
                        <p class="text-gray-600">Meluncurkan platform booking online dan sistem check-in digital untuk pengalaman tamu yang lebih baik</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="bg-gradient-to-r from-blue-900 to-blue-700 text-white rounded-lg p-12 text-center">
        <h2 class="text-4xl font-bold mb-4">Siap Merasakan Pengalaman Azure?</h2>
        <p class="text-xl mb-8 opacity-90">Pesan kamar Anda sekarang dan nikmati pelayanan terbaik dari kami</p>
        <a href="{{ route('rooms.index') }}" class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-bold px-8 py-4 rounded-lg transition-colors text-lg shadow-lg hover:shadow-xl">
            Reservasi Sekarang
        </a>
    </section>
</div>
@endsection