@extends('layouts.user')

@section('title', 'Daftar Kamar - Grand Azure Hotel')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/rooms.css') }}">
@endpush

@section('content')

<!-- Header Section -->
<section class="rooms-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="rooms-header-title">Daftar Kamar</h1>
        <p class="rooms-header-subtitle">Pilih kamar yang sesuai dengan kebutuhan Anda</p>
    </div>
</section>

<!-- Filters & Rooms Section -->
<section class="rooms-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Filters -->
        <div class="filter-card">
            <form action="{{ route('rooms.index') }}" method="GET" id="filter-form">
                <div class="filter-grid">
                    <div class="filter-item">
                        <label class="filter-label">Harga</label>
                        <div class="custom-select-wrapper">
                            <select name="price" class="filter-select">
                                <option value="">Semua Harga</option>
                                <option value="low" {{ request('price') == 'low' ? 'selected' : '' }}>Di bawah Rp 1.000.000</option>
                                <option value="mid" {{ request('price') == 'mid' ? 'selected' : '' }}>Rp 1.000.000 - Rp 2.000.000</option>
                                <option value="high" {{ request('price') == 'high' ? 'selected' : '' }}>Di atas Rp 2.000.000</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-item">
                        <label class="filter-label">Kapasitas</label>
                        <div class="custom-select-wrapper">
                            <select name="capacity" class="filter-select">
                                <option value="">Semua Kapasitas</option>
                                <option value="2" {{ request('capacity') == '2' ? 'selected' : '' }}>2 Tamu</option>
                                <option value="3" {{ request('capacity') == '3' ? 'selected' : '' }}>3 Tamu</option>
                                <option value="4" {{ request('capacity') == '4' ? 'selected' : '' }}>4+ Tamu</option>
                            </select>
                        </div>
                    </div>

                    <div class="filter-item">
                        <label class="filter-label">Fasilitas</label>
                        <div class="custom-select-wrapper">
                            <select name="facility" class="filter-select">
                                <option value="">Semua Fasilitas</option>
                                @foreach($facilities as $facility)
                                    <option value="{{ $facility->id }}" {{ request('facility') == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-filter-apply">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                        </svg>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('rooms.index') }}" class="btn-filter-reset">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                        </svg>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Rooms Grid -->
        <div class="rooms-grid">
            @forelse($rooms as $room)
                {{-- ✅ MENGGUNAKAN COMPONENT ROOM-CARD --}}
                <x-room-card :room="$room" />
            @empty
                <div class="empty-state">
                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <p class="empty-text">Tidak ada kamar yang tersedia dengan filter tersebut</p>
                    <p class="text-gray-400 text-sm mt-2 mb-6">Coba ubah filter atau reset pencarian Anda</p>
                    <a href="{{ route('rooms.index') }}" class="btn-back-home">Reset Filter</a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($rooms->hasPages())
        <div class="pagination-wrapper">
            {{ $rooms->links() }}
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/user/rooms.js') }}"></script>
@endpush