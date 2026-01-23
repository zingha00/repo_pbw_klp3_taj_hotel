@extends('layouts.user')

@section('title', 'Pemesanan Kamar - Grand Azure Hotel')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/booking.css') }}">
@endpush

@section('content')
<section class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold text-gray-900 mb-2">Pemesanan Kamar</h1>
            <p class="text-gray-600">Lengkapi data di bawah untuk melanjutkan pemesanan</p>
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" id="booking-form">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->id }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Booking Form -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Data Pemesan -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h2 class="font-semibold text-lg mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Data Pemesan
                        </h2>

                        @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-4 text-sm">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <ul class="space-y-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name ?? '') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                       placeholder="Masukkan nama lengkap"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       value="{{ old('email', auth()->user()->email ?? '') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                       placeholder="email@example.com"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                       placeholder="08xxxxxxxxxx"
                                       pattern="[0-9]{10,13}"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">Format: 08xxxxxxxxxx (10-13 digit)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pemesanan -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h2 class="font-semibold text-lg mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Detail Pemesanan
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Check-in <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="check_in" 
                                       id="check_in"
                                       value="{{ old('check_in', $checkin ?? date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Check-out <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       name="check_out" 
                                       id="check_out"
                                       value="{{ old('check_out', $checkout ?? date('Y-m-d', strtotime('+1 day')) ) }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                       required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Tamu <span class="text-red-500">*</span>
                                </label>
                                <select name="guests" 
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                        required>
                                    @for($i = 1; $i <= $room->capacity; $i++)
                                        <option value="{{ $i }}" {{ old('guests', 2) == $i ? 'selected' : '' }}>
                                            {{ $i }} Orang
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Jumlah Kamar <span class="text-red-500">*</span>
                                </label>
                                <select name="rooms_count" 
                                        id="rooms_count"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                        required>
                                    @for($i = 1; $i <= min(5, $room->stock); $i++)
                                        <option value="{{ $i }}" {{ old('rooms_count', 1) == $i ? 'selected' : '' }}>
                                            {{ $i }} Kamar
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <!-- Nights Info -->
                        <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-sm text-amber-800">
                                <span class="font-semibold" id="nights-display">1</span> malam menginap
                            </p>
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h2 class="font-semibold text-lg mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Catatan Tambahan (Opsional)
                        </h2>
                        <textarea name="notes" 
                                  rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                  placeholder="Permintaan khusus, alergi makanan, dll.">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <!-- Right Column: Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl p-6 shadow-sm sticky top-4">
                        <h2 class="font-semibold text-lg mb-4">Ringkasan Pesanan</h2>

                        <!-- Room Info -->
                        <div class="mb-4 pb-4 border-b">
                            <div class="flex gap-3">
                                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-700 rounded-lg flex-shrink-0"></div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $room->name }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $room->size }} • {{ $room->capacity }} Tamu</p>
                                </div>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-4 pb-4 border-b">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">
                                    Rp {{ number_format($room->price, 0, ',', '.') }} x <span id="summary-nights">1</span> malam x <span id="summary-rooms">1</span> kamar
                                </span>
                                <span class="font-medium text-gray-900" id="subtotal-display">
                                    Rp {{ number_format($room->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Pajak & Layanan (10%)</span>
                                <span class="font-medium text-gray-900" id="tax-display">
                                    Rp {{ number_format($room->price * 0.1, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center mb-6">
                            <span class="font-semibold text-gray-900">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-amber-600" id="total-display">
                                Rp {{ number_format($room->price * 1.1, 0, ',', '.') }}
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-semibold py-3 rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                            Lanjutkan ke Pembayaran
                        </button>

                        <!-- Info -->
                        <div class="mt-4 space-y-2">
                            <div class="flex items-start gap-2 text-xs text-gray-600">
                                <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Konfirmasi instan setelah pembayaran</span>
                            </div>
                            <div class="flex items-start gap-2 text-xs text-gray-600">
                                <svg class="w-4 h-4 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Pembatalan gratis dalam 24 jam</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const roomPrice = Number(form.dataset.roomPrice);
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const roomsCountInput = document.getElementById('rooms_count');
    
    function calculateTotal() {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const roomsCount = parseInt(roomsCountInput.value) || 1;
        
        // Calculate nights
        const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        
        if (nights > 0) {
            // Update nights display
            document.getElementById('nights-display').textContent = nights;
            document.getElementById('summary-nights').textContent = nights;
            document.getElementById('summary-rooms').textContent = roomsCount;
            
            // Calculate subtotal
            const subtotal = roomPrice * nights * roomsCount;
            const tax = subtotal * 0.1;
            const total = subtotal + tax;
            
            // Update displays
            document.getElementById('subtotal-display').textContent = 
                'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('tax-display').textContent = 
                'Rp ' + tax.toLocaleString('id-ID');
            document.getElementById('total-display').textContent = 
                'Rp ' + total.toLocaleString('id-ID');
        }
    }
    
    // Update checkout min date when checkin changes
    checkInInput.addEventListener('change', function() {
        const checkInDate = new Date(this.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(nextDay.getDate() + 1);
        
        checkOutInput.min = nextDay.toISOString().split('T')[0];
        
        if (new Date(checkOutInput.value) <= checkInDate) {
            checkOutInput.value = nextDay.toISOString().split('T')[0];
        }
        
        calculateTotal();
    });
    
    checkOutInput.addEventListener('change', calculateTotal);
    roomsCountInput.addEventListener('change', calculateTotal);
    
    // Initial calculation
    calculateTotal();
    
    // Form validation
    document.getElementById('booking-form').addEventListener('submit', function(e) {
        const phone = document.querySelector('input[name="phone"]').value;
        if (!/^[0-9]{10,13}$/.test(phone)) {
            e.preventDefault();
            alert('Nomor telepon harus 10-13 digit angka!');
            return false;
        }
    });
});
</script>
@endpush