@extends('layouts.user')

@section('title', $room->name . ' - Grand Azure Hotel')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/room-detail.css') }}">
@endpush

@section('content')

<!-- Breadcrumb -->
<section class="breadcrumb-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('rooms.index') }}" class="breadcrumb-link">Kamar</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">{{ $room->name }}</span>
        </nav>
    </div>
</section>

@php
// Ambil images dari relasi
$roomImages = $room->relationLoaded('images') ? $room->images : collect();
$imageCount = $roomImages->count();
$hasImages = $imageCount > 0;

// Ambil primary image atau first image
$primaryImage = $roomImages->where('is_primary', true)->first()
?? $roomImages->first();

$firstImagePath = $primaryImage ? $primaryImage->image_path : null;
@endphp

<!-- Main Booking Section - 2 Column Layout -->
<section class="booking-main-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="booking-container">

            <!-- LEFT: Room Image & Details -->
            <div class="room-image-section">
                <!-- Image Counter Badge -->
                @if($hasImages)
                <div class="image-counter-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    {{ $imageCount }} Foto
                </div>
                @endif

                <!-- Room Type Badge -->
                @if(isset($room->type))
                <div class="room-type-badge">
                    {{ $room->type }}
                </div>
                @endif

                <!-- Main Image -->
                <div class="main-image-wrapper">
                    @if($hasImages)
                    <img src="{{ asset('storage/' . $firstImagePath) }}"
                        alt="{{ $room->name }}"
                        class="main-room-image"
                        id="mainImage"
                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-placeholder\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.5\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'></rect><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'></circle><polyline points=\'21 15 16 10 5 21\'></polyline></svg><p style=\'margin-top: 1rem; color: #9ca3af;\'>Gambar tidak tersedia</p></div>';">
                    @elseif($room->image)
                    {{-- Fallback ke field image jika ada --}}
                    <img src="{{ asset('storage/' . $room->image) }}"
                        alt="{{ $room->name }}"
                        class="main-room-image"
                        id="mainImage"
                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-placeholder\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'80\' height=\'80\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.5\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'></rect><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'></circle><polyline points=\'21 15 16 10 5 21\'></polyline></svg><p style=\'margin-top: 1rem; color: #9ca3af;\'>Gambar tidak tersedia</p></div>';">
                    @else
                    <div class="image-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        <p style="margin-top: 1rem; color: #9ca3af;">Tidak ada foto</p>
                    </div>
                    @endif
                </div>

                <!-- Thumbnails Grid - Show if more than 1 image -->
                @if($imageCount > 1)
                <div class="thumbnails-grid">
                    @foreach($roomImages as $index => $image)
                    <div class="thumbnail-wrapper {{ ($image->is_primary ?? false) || $index === 0 ? 'active' : '' }}"
                        onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)"
                        data-index="{{ $index }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            alt="{{ $room->name }} - Foto {{ $index + 1 }}"
                            class="thumbnail-image"
                            onerror="this.parentElement.style.display='none';">
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Room Title Section -->
                <div class="room-title-section">
                    <h1 class="room-title">{{ $room->name }}</h1>
                    <div class="room-specs-inline">
                        @if(isset($room->floor))
                        <span>Lantai {{ $room->floor }}</span>
                        <span>•</span>
                        @endif
                        <span>{{ $room->size ?? 'N/A' }}</span>
                        <span>•</span>
                        <span>Max {{ $room->capacity ?? 2 }} Tamu</span>
                    </div>
                </div>

                <!-- Room Description -->
                <div class="room-description-box">
                    <p>{{ $room->description ?? 'Tidak ada deskripsi tersedia.' }}</p>
                </div>

                <!-- Facilities Section -->
                <div class="facilities-section">
                    <h3 class="facilities-title">FASILITAS KAMAR</h3>
                    <div class="facilities-list">
                        @if($room->relationLoaded('facilities') && $room->facilities->count() > 0)
                        @foreach($room->facilities as $facility)
                        <div class="facility-badge">
                            @php
                            $facilityName = strtolower($facility->name ?? '');
                            $facilityIcons = [
                            'wifi' => '
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />',
                            'internet' => '
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />',
                            'ac' => '
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707" />',
                            'tv' => '<rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                            <polyline points="17 2 12 7 7 2"></polyline>',
                            'smart tv' => '<rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                            <polyline points="17 2 12 7 7 2"></polyline>',
                            'televisi' => '<rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                            <polyline points="17 2 12 7 7 2"></polyline>',
                            'bathtub' => '<path d="M9 6a3 3 0 1 0-6 0"></path>
                            <path d="M3 8h18v10H3z"></path>
                            <path d="M21 8V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2"></path>',
                            'bath' => '<path d="M9 6a3 3 0 1 0-6 0"></path>
                            <path d="M3 8h18v10H3z"></path>
                            <path d="M21 8V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2"></path>',
                            'minibar' => '<circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 1v6m0 6v6m5.2-5.2l-4.2-4.2m0 8.4l4.2-4.2M6.8 6.8l4.2 4.2m0-8.4L6.8 17.2" />',
                            'mini bar' => '<circle cx="12" cy="12" r="3"></circle>
                            <path d="M12 1v6m0 6v6m5.2-5.2l-4.2-4.2m0 8.4l4.2-4.2M6.8 6.8l4.2 4.2m0-8.4L6.8 17.2" />'
                            ];

                            // Cari icon yang cocok
                            $icon = null;
                            foreach ($facilityIcons as $key => $value) {
                            if (str_contains($facilityName, $key)) {
                            $icon = $value;
                            break;
                            }
                            }

                            // Default icon jika tidak ketemu
                            if (!$icon) {
                            $icon = '<polyline points="20 6 9 17 4 12"></polyline>';
                            }
                            @endphp
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                {!! $icon !!}
                            </svg>
                            <span>{{ $facility->name }}</span>
                        </div>
                        @endforeach
                        @else
                        <p class="no-facilities">Tidak ada informasi fasilitas</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- RIGHT: Booking Form -->
            <div class="booking-form-section">
                <div class="booking-card-new">

                    <!-- Discount Badge -->
                    @if(isset($room->discount) && $room->discount > 0)
                    <div class="discount-badge">
                        🔥 Diskon {{ $room->discount }}%
                    </div>
                    @endif

                    <!-- Price Section -->
                    <div class="price-header">
                        <div>
                            <p class="price-label">Harga mulai dari</p>
                            <p class="price-amount">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                            <p class="price-period">/malam</p>
                        </div>
                    </div>

                    @if($room->stock > 0 && $room->status === 'available')
                    <!-- Booking Form -->
                    <form action="{{ route('bookings.store') }}"
                        method="POST"
                        class="booking-form"
                        id="bookingForm"
                        onsubmit="console.log('Form onsubmit fired');">
                        @csrf

                        <input type="hidden" name="room_id" value="{{ $room->id }}">

                        <!-- Check-in & Check-out -->
                        <div class="date-inputs-row">
                            <div class="form-group-half">
                                <label class="input-label">Check-in</label>
                                <input type="date"
                                    name="check_in"
                                    class="date-input"
                                    required
                                    min="{{ date('Y-m-d') }}"
                                    value="{{ old('check_in', date('Y-m-d')) }}">
                            </div>

                            <div class="form-group-half">
                                <label class="input-label">Check-out</label>
                                <input type="date"
                                    name="check_out"
                                    class="date-input"
                                    required
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}">
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="form-group">
                            <label class="input-label">Nama Lengkap</label>
                            <input type="text"
                                name="name"
                                class="text-input"
                                placeholder="Masukkan nama sesuai KTP"
                                required
                                value="{{ old('name', auth()->user()->name ?? '') }}">
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="input-label">Email</label>
                            <input type="email"
                                name="email"
                                class="text-input"
                                placeholder="email@contoh.com"
                                required
                                value="{{ old('email', auth()->user()->email ?? '') }}">
                        </div>

                        <!-- No. Telepon -->
                        <div class="form-group">
                            <label class="input-label">No. Telepon</label>
                            <input type="tel"
                                name="phone"
                                class="text-input"
                                placeholder="+62 812 3456 7890"
                                required
                                value="{{ old('phone') }}">
                        </div>

                        <!-- Jumlah Tamu & Kamar -->
                        <div class="quantity-inputs-row">
                            <div class="form-group-half">
                                <label class="input-label">Jumlah Tamu</label>
                                <select name="guests" class="select-input" required>
                                    @for($i = 1; $i <= $room->capacity; $i++)
                                        <option value="{{ $i }}" {{ old('guests', 1) == $i ? 'selected' : '' }}>
                                            {{ $i }} Tamu
                                        </option>
                                        @endfor
                                </select>
                            </div>

                            <div class="form-group-half">
                                <label class="input-label">Jumlah Kamar</label>
                                <select name="rooms_count" class="select-input" required>
                                    @for($i = 1; $i <= min($room->stock, 5); $i++)
                                        <option value="{{ $i }}" {{ old('rooms_count', 1) == $i ? 'selected' : '' }}>
                                            {{ $i }} Kamar
                                        </option>
                                        @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Permintaan Khusus -->
                        {{-- <div class="form-group">
                            <label class="input-label">
                                Pesan <span class="optional">(opsional)</span>
                            </label>
                            <textarea name="special_requests"
                                class="textarea-input"
                                rows="3"
                                placeholder="Contoh: Kamar lantai tinggi, extra bed, dll.">{{ old('special_requests') }}</textarea>
                        </div> --}}

                        <!-- Submit Button -->
                        @auth
                        <button type="submit"
                            class="submit-button"
                            id="bookingSubmitBtn">
                            <span>Booking Sekarang →</span>
                        </button>
                        @else
                        <a href="{{ route('login') }}"
                            class="submit-button"
                            id="loginButton">
                            <span>Login untuk Memesan →</span>
                        </a>
                        @endauth

                        <!-- Security Note -->
                        <div class="security-note">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            Pembayaran aman & terenkripsi
                        </div>
                    </form>
                    @else
                    <div class="sold-out-message">
                        <p>Kamar ini sedang tidak tersedia</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Rooms -->
@if(isset($relatedRooms) && $relatedRooms->count() > 0)
<section class="related-rooms-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="section-header">
            <h2 class="section-title">Kamar Lainnya</h2>
            <p class="section-subtitle">Rekomendasi kamar serupa untuk Anda</p>
        </div>

        <div class="rooms-grid mt-8">
            @foreach($relatedRooms as $relatedRoom)
            @php
            // Ambil primary image dari relasi
            $relatedImages = $relatedRoom->relationLoaded('images') ? $relatedRoom->images : collect();
            $relatedPrimaryImage = $relatedImages->where('is_primary', true)->first()
            ?? $relatedImages->first();
            $hasRelatedImage = $relatedPrimaryImage !== null;
            @endphp

            <div class="room-card">
                <div class="room-card-image-container">
                    @if($hasRelatedImage)
                    <img src="{{ asset('storage/' . $relatedPrimaryImage->image_path) }}"
                        alt="{{ $relatedRoom->name }}"
                        class="room-card-image"
                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'room-card-placeholder\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'64\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.5\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'></rect><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'></circle><polyline points=\'21 15 16 10 5 21\'></polyline></svg></div>';">
                    @elseif($relatedRoom->image)
                    <img src="{{ asset('storage/' . $relatedRoom->image) }}"
                        alt="{{ $relatedRoom->name }}"
                        class="room-card-image"
                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'room-card-placeholder\'><svg xmlns=\'http://www.w3.org/2000/svg\' width=\'64\' height=\'64\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'1.5\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'></rect><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'></circle><polyline points=\'21 15 16 10 5 21\'></polyline></svg></div>';">
                    @else
                    <div class="room-card-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                    </div>
                    @endif

                    {{-- <div class="card-rating">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                        {{ number_format($relatedRoom->rating ?? 0, 1) }}
                    </div> --}}
                </div>

                <div class="room-card-body">
                    <h3 class="card-title">{{ $relatedRoom->name }}</h3>

                    <div class="card-details">
                        <span>{{ $relatedRoom->size ?? 'N/A' }}</span>
                        <span>•</span>
                        <span>{{ $relatedRoom->capacity ?? 2 }} Tamu</span>
                    </div>

                    <div class="card-footer">
                        <div>
                            <div class="card-price">Rp {{ number_format($relatedRoom->price, 0, ',', '.') }}</div>
                            <div class="card-price-label">/malam</div>
                        </div>

                        <a href="{{ route('rooms.show', $relatedRoom->slug ?? $relatedRoom->id) }}" class="btn-view">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
    console.log('Show blade scripts loading...');

    // Change main image when thumbnail clicked
    function changeMainImage(imageSrc, thumbnailElement) {
        const mainImage = document.getElementById('mainImage');
        if (mainImage) {
            // Fade out
            mainImage.style.opacity = '0';

            // Update active thumbnail
            document.querySelectorAll('.thumbnail-wrapper').forEach(thumb => {
                thumb.classList.remove('active');
            });
            if (thumbnailElement) {
                thumbnailElement.classList.add('active');
            }

            // Change image and fade in
            setTimeout(() => {
                mainImage.src = imageSrc;
                mainImage.style.opacity = '1';
            }, 200);
        }
    }

    // Prevent date inconsistency
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM Content Loaded');

        const checkInInput = document.querySelector('input[name="check_in_date"]');
        const checkOutInput = document.querySelector('input[name="check_out_date"]');

        if (checkInInput && checkOutInput) {
            checkInInput.addEventListener('change', function() {
                const checkInDate = new Date(this.value);
                const minCheckOut = new Date(checkInDate);
                minCheckOut.setDate(minCheckOut.getDate() + 1);

                const minCheckOutStr = minCheckOut.toISOString().split('T')[0];
                checkOutInput.setAttribute('min', minCheckOutStr);

                // If check-out is before new min, update it
                if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                    checkOutInput.value = minCheckOutStr;
                }
            });
        }

        // Debug submit button
        const submitBtn = document.getElementById('bookingSubmitBtn');
        const loginBtn = document.getElementById('loginButton');

        if (submitBtn) {
            console.log('Submit button found:', submitBtn);
            console.log('Button disabled:', submitBtn.disabled);
            console.log('Button type:', submitBtn.type);

            submitBtn.addEventListener('click', function(e) {
                console.log('Submit button clicked!');
            });
        }

        if (loginBtn) {
            console.log('Login button found:', loginBtn);
            console.log('Login href:', loginBtn.href);
        }

        // Debug form
        const form = document.querySelector('.booking-form');
        if (form) {
            console.log('Form found:', form);
            console.log('Form action:', form.action);
            console.log('Form method:', form.method);

            form.addEventListener('submit', function(e) {
                console.log('Form submitting...');
                console.log('Form data:', new FormData(form));
            });
        }
    });
</script>
<script src="{{ asset('assets/js/user/room-detail.js') }}"></script>
@endpush