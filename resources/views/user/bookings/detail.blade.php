@extends('layouts.user')

@section('title', 'Detail Pemesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/bookings.css') }}">
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <a href="{{ route('reservations.index') }}"
        class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 19l-7-7 7-7" />
        </svg>
        Kembali
    </a>

    <div class="bg-white rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-start mb-6">
            <div>
                <p class="text-sm text-gray-500">Kode Booking</p>
                <p class="font-mono font-bold text-2xl">{{ $booking->booking_code }}</p>
            </div>
            @php
            $statusClass = match ($booking->status) {
            'pending' => 'status-pending',
            'waiting_verification' => 'status-verification',
            'paid', 'confirmed' => 'status-paid',
            'completed' => 'status-completed',
            'cancelled' => 'status-cancelled',
            default => 'status-cancelled',
            };

            $statusLabel = match ($booking->status) {
            'pending' => 'Menunggu Pembayaran',
            'waiting_verification' => 'Menunggu Verifikasi',
            'paid' => 'Dibayar',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Status Tidak Dikenal',
            };
            @endphp

            <span class="{{ $statusClass }} px-4 py-2 rounded-full font-medium">
                {{ $statusLabel }}
            </span>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h3 class="font-semibold mb-3">Detail Kamar</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama Kamar</span>
                        <span class="font-medium">{{ $booking->room->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Check-in</span>
                        <span class="font-medium">
                            {{ \Carbon\Carbon::parse($booking->check_in)->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Check-out</span>
                        <span class="font-medium">
                            {{ \Carbon\Carbon::parse($booking->check_out)->translatedFormat('l, d F Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="font-semibold mb-3">Data Pemesan</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Nama</span>
                        <span class="font-medium">{{ $booking->user_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email</span>
                        <span class="font-medium">{{ $booking->user_email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telepon</span>
                        <span class="font-medium">{{ $booking->user_phone }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t pt-4">
            @if(in_array($booking->status, ['pending', 'waiting_verification']))
    <div class="mt-6 pt-6 border-t flex justify-end">
        <button
            onclick="openCancelModal()"
            class="px-5 py-2.5 rounded-lg border border-red-200
                   text-red-600 font-medium
                   hover:bg-red-50 transition">
            Batalkan Pesanan
        </button>
    </div>
@endif
            <div class="flex justify-between items-center">
                <span class="text-gray-500">Total Pembayaran</span>
                <span class="font-bold text-2xl gold-accent">
                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                </span>
            </div>
            @if($booking->payment_method)
            <p class="text-sm text-gray-500 mt-2">
                Metode: {{ $booking->payment_method === 'bank' ? 'Transfer Bank' : 'E-Wallet' }}
            </p>
            @endif
        </div>

        @if($booking->review)
        <div class="mt-6 pt-6 border-t">
            <h3 class="font-semibold mb-3">Ulasan Anda</h3>
            <div class="flex items-center gap-1 mb-2">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= $booking->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    @endfor
            </div>
            <p class="text-gray-600">{{ $booking->review }}</p>
        </div>
        @endif
    </div>

    <!-- Review Form -->
    @if($booking->status === 'completed' && !$booking->review)
    <div class="bg-white rounded-xl p-6 shadow-sm mt-6">
        <h3 class="font-semibold text-lg mb-4">Berikan Ulasan</h3>

        <form action="{{ route('user.review.store') }}" method="POST">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                <div id="rating-stars" class="flex gap-2">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})"
                        class="rating-star text-3xl text-gray-300 hover:text-yellow-400">
                        ★
                        </button>
                        @endfor
                </div>
                <input type="hidden" name="rating" id="rating-value" value="0" required>
                @error('rating')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ulasan</label>
                <textarea name="review" id="review-text" rows="4" required
                    class="w-full px-4 py-3 border border-gray-200 rounded-lg"
                    placeholder="Ceritakan pengalaman menginap Anda...">{{ old('review') }}</textarea>
                @error('review')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" id="review-submit-btn"
                class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                Kirim Ulasan
            </button>
        </form>
    </div>
    @endif
</div>

<div id="cancelModal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeCancelModal()"></div>

    <div class="relative bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-xl">
        <h3 class="text-lg font-semibold mb-2">Batalkan Pesanan</h3>
        <p class="text-gray-600 mb-6">
            Pesanan yang dibatalkan tidak dapat dikembalikan.
            Apakah Anda yakin?
        </p>

        <div class="flex justify-end gap-3">
            <button onclick="closeCancelModal()"
                class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50">
                Kembali
            </button>

            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Ya, Batalkan
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('flex');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('flex');
}
</script>

<script>
    let selectedRating = 0;

    function setRating(rating) {
        selectedRating = rating;
        document.getElementById('rating-value').value = rating;
        updateRatingStars();
    }

    function updateRatingStars() {
        document.querySelectorAll('.rating-star').forEach((star, index) => {
            if (index < selectedRating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
</script>
@endpush