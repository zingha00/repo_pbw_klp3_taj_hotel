@extends('layouts.user')

@section('title', 'Pesanan Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/reservation.css') }}">
@endpush

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-bold text-gray-900 mb-8">Pesanan Saya</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    @if($bookings->count() > 0)
    <div class="space-y-4">
        @foreach($bookings as $booking)
        <div class="bg-white rounded-xl p-6 shadow-sm fade-in">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <!-- Room Info -->
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-200 flex-shrink-0">
    <img
        src="{{ $booking->room->primary_image }}"
        alt="{{ $booking->room->name }}"
        class="w-full h-full object-cover"
        loading="lazy"
    >
</div>

                    <div>
                        <p class="font-mono text-sm text-gray-500">{{ $booking->booking_code }}</p>
                        <h3 class="font-semibold text-lg">{{ $booking->room->name }}</h3>
                        <p class="text-gray-500 text-sm">
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') }}
                            <span class="text-gray-400">•</span>
                            {{ $booking->nights }} malam
                        </p>
                    </div>
                </div>

                <!-- Status & Price -->
                <div class="flex flex-col md:items-end gap-2">
                    @php
                    $statusClass = match ($booking->status) {
                    'pending', 'waiting_verification' => 'status-pending',
                    'confirmed' => 'status-paid',
                    'paid' => 'status-paid',
                    'completed' => 'status-completed',
                    default => 'status-cancelled',
                    };

                    $statusLabel = match ($booking->status) {
                    'pending', 'waiting_verification' => 'Menunggu Pembayaran',
                    'confirmed' => 'Dikonfirmasi',
                    'paid' => 'Dibayar',
                    'completed' => 'Selesai',
                    default => 'Dibatalkan',
                    };
                    @endphp

                    <span class="{{ $statusClass }} px-3 py-1 rounded-full text-sm font-medium">
                        {{ $statusLabel }}
                    </span>

                    <p class="font-bold gold-accent text-xl">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </p>
                </div>

            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mt-4 pt-4 border-t">
                <a href="{{ route('bookings.detail', $booking->id) }}"
                    class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                    Lihat Detail
                </a>

                @if($booking->status === 'pending')
                {{-- <a href="{{ route('payment.index', $booking->id) }}"
                    class="text-sm font-medium gold-accent hover:underline transition">
                    Bayar Sekarang
                </a> --}}
                {{-- <button onclick="showCancelModal('{{ $booking->id }}')"
                    class="text-sm font-medium text-red-600 hover:underline transition">
                    Batalkan
                </button> --}}
                @endif

                @if($booking->status === 'paid')
                <form action="{{ route('bookings.complete', $booking->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-sm font-medium text-green-600 hover:underline transition">
                        Tandai Selesai
                    </button>
                </form>
                @endif

                @if($booking->status === 'completed' && !$booking->review)
                <a href="{{ route('bookings.detail', $booking->id) }}"
                    class="text-sm font-medium gold-accent hover:underline transition">
                    Beri Ulasan
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $bookings->links() }}
    </div>

    @else
    <!-- No Reservations -->
    <div class="text-center py-16">
        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-gray-500 text-lg mb-4">Belum ada pesanan</p>
        <a href="{{ route('rooms.index') }}"
            class="btn-primary text-white px-6 py-3 rounded-lg font-semibold inline-block">
            Pesan Kamar Sekarang
        </a>
    </div>
    @endif
</div>

<!-- Cancel Booking Modal -->
<div id="cancel-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeCancelModal()"></div>
    <div class="relative bg-white rounded-xl p-6 max-w-sm mx-4 shadow-2xl fade-in">
        <h3 class="font-semibold text-lg mb-2">Batalkan Pesanan</h3>
        <p class="text-gray-500 mb-6">Apakah Anda yakin ingin membatalkan pesanan ini?</p>

        <form id="cancel-form" method="POST">
            @csrf
            <div class="flex gap-3">
                <button type="button" onclick="closeCancelModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition">
                    Tidak
                </button>
                <button type="submit" id="cancel-confirm-btn"
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                    Ya, Batalkan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/user/reservation.js') }}"></script>
@endpush