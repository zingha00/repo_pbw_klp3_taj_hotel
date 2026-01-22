@extends('layouts.admin')

@section('title', 'Kelola Pemesanan')

@push('styles')
<style>
    /* Custom animations for modal */
    .animate-in {
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    /* Hover effects for buttons */
    .btn-view-reason {
        transition: all 0.2s ease-in-out;
    }
    
    .btn-view-reason:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }
    
    /* Loading spinner */
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Custom scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Pemesanan</h1>
        <p class="text-gray-600 mt-1">Manage semua pemesanan kamar hotel</p>
    </div>


    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Menunggu</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['pending'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Dibayar</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['waiting_verification'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Selesai</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['confirmed'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Dibatalkan</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['cancelled'] ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama/email..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">

            <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="waiting_verification" {{ request('status') == 'waiting_verification' ? 'selected' : '' }}>Dibayar</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <select name="show_deleted" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="">Aktif Saja</option>
                <option value="with" {{ request('show_deleted') == 'with' ? 'selected' : '' }}>Semua (+ Dihapus)</option>
                <option value="only" {{ request('show_deleted') == 'only' ? 'selected' : '' }}>Dihapus Saja</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">

            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">

            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
                <a href="{{ route('admin.bookings.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Reset</a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
    <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-[12%]">Kode Booking</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-[15%]">Tamu</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-[12%]">Kamar</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-[15%]">Check-in/out</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-[12%]">Total</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-[15%]">Status</th>
        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-[12%]">Aksi</th>
        <!-- Tambah w-[12%] dan text-center -->
    </tr>
</thead>
                <tbody class="bg-white divide-y divide-gray-200">
    @forelse($bookings as $booking)
    <tr class="hover:bg-gray-50 {{ $booking->trashed() ? 'bg-red-50' : '' }}">
        <td class="px-6 py-4">
            <div class="font-mono text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
            <div class="text-xs text-gray-500">{{ $booking->created_at ? $booking->created_at->format('d M Y H:i') : '-' }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-gray-900">{{ $booking->user_name }}</div>
            <div class="text-sm text-gray-500">{{ $booking->user_email }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-medium text-gray-900">{{ optional($booking->room)->name ?? 'Kamar dihapus' }}</div>
            <div class="text-xs text-gray-500">{{ $booking->guests }} Tamu</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900">{{ $booking->check_in ? $booking->check_in->format('d M Y') : '-' }}</div>
            <div class="text-sm text-gray-500">{{ $booking->check_out ? $booking->check_out->format('d M Y') : '-' }}</div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</div>
        </td>
        
        <!-- KOLOM STATUS - Badge status di sini saja -->
        <td class="px-6 py-4">
            @if($booking->trashed())
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    Dihapus
                </span>
            @else
                <span class="
                    inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($booking->status === 'waiting_verification') bg-blue-100 text-blue-800
                    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                    @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif
                ">
                    @if($booking->status === 'pending') Menunggu Pembayaran
                    @elseif($booking->status === 'waiting_verification') Menunggu Verifikasi
                    @elseif($booking->status === 'confirmed') ✓ Terkonfirmasi
                    @elseif($booking->status === 'cancelled') Dibatalkan
                    @else {{ ucfirst($booking->status) }}
                    @endif
                </span>
            @endif
        </td>
        
        <!-- KOLOM AKSI - Hanya tombol aksi, tanpa duplikat badge -->
        <!-- KOLOM AKSI - Tidak terlalu mepet ke kanan -->
<td class="px-6 py-4 text-center">
    <!-- Ganti jadi text-center -->
    @if($booking->trashed())
    <button type="button"
    onclick="restoreBooking({{ $booking->id }})"
    class="px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Pulihkan
    </button>

    @else
        @if($booking->status === 'waiting_verification')
            <button type="button" data-booking-id="{{ $booking->id }}" class="btn-view-detail px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                Lihat Detail
            </button>
        @elseif($booking->status === 'confirmed')
            <div class="flex gap-2 justify-center">
                <button type="button" onclick="openCancelModal({{ $booking->id }})" 
                        class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm transition-colors duration-200"
                        title="Batalkan booking ini">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </button>
            </div>
        @elseif($booking->status === 'cancelled')
            <button type="button" onclick="viewReason({{ $booking->id }})" 
                    class="btn-view-reason inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-50 to-red-100 border border-red-200 text-red-700 rounded-lg hover:from-red-100 hover:to-red-200 hover:border-red-300 text-sm font-medium transition-all duration-200 shadow-sm"
                    title="Klik untuk melihat alasan pembatalan">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="gradient-text font-semibold">Lihat Alasan</span>
            </button>
        @elseif($booking->status === 'pending')
            <span class="text-xs text-gray-400">Belum dibayar</span>
        @endif
    @endif
</td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="font-medium">Tidak ada pemesanan</p>
        </td>
    </tr>
    @endforelse
</tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t">{{ $bookings->links() }}</div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/70" onclick="closeDetailModal()"></div>
    <div class="relative bg-white rounded-xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
        <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-6">
            <h2 class="text-2xl font-bold mb-6">Detail Pemesanan & Pembayaran</h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-lg mb-4">Informasi Pemesanan</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        <div class="flex justify-between"><span class="text-gray-600">Kode</span><span id="detail-booking-code" class="font-mono font-semibold">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Kamar</span><span id="detail-room">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Nama</span><span id="detail-guest-name">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Email</span><span id="detail-guest-email">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Check-in</span><span id="detail-checkin">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Check-out</span><span id="detail-checkout">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Tamu</span><span id="detail-guests">-</span></div>
                        <div class="flex justify-between"><span class="text-gray-600">Total</span><span id="detail-total" class="font-bold text-lg">-</span></div>
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold text-lg mb-4">Bukti Transfer</h3>
                    <div class="bg-gray-100 rounded-lg p-4">
                        <img id="detail-proof-image" src="" alt="Bukti" class="w-full rounded-lg cursor-pointer" onclick="window.open(this.src, '_blank')">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-3 border-t pt-6">
                <button onclick="closeDetailModal()" class="flex-1 px-4 py-3 border rounded-lg hover:bg-gray-50">Tutup</button>
                <button onclick="showRejectModal()" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">Tolak</button>
                <button onclick="confirmBooking()" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div id="cancel-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeCancelModal()"></div>
    <div class="relative bg-white rounded-xl p-6 max-w-md w-full shadow-2xl transform transition-all">
        <!-- Close button (X) di pojok kanan atas -->
        <button onclick="closeCancelModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <div class="flex items-center mb-4 pr-8">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="font-semibold text-lg text-gray-900">Batalkan Booking</h3>
        </div>
        
        <!-- Alert gabungan -->
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Peringatan:</strong> Apakah Anda yakin ingin membatalkan booking ini? 
                        <br><br>
                        <strong>Catatan:</strong> Alasan pembatalan minimal 10 karakter. Tindakan ini dapat di-restore kembali jika diperlukan.
                    </p>
                </div>
            </div>
        </div>
        
        <form onsubmit="submitCancel(event)" id="cancel-form">
            <input type="hidden" id="cancel-booking-id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Pembatalan <span class="text-red-500">*</span>
                </label>
                <textarea id="cancellation-reason" rows="4" required maxlength="500" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none" 
                          placeholder="Masukkan alasan pembatalan booking..."></textarea>
                <div class="text-xs text-gray-500 mt-1">
                    <span id="char-count">0</span>/500 karakter
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" id="cancel-submit-btn"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span id="cancel-btn-text">Batalkan Booking</span>
                    <span id="cancel-btn-loading" class="hidden">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/50" onclick="closeRejectModal()"></div>
    <div class="relative bg-white rounded-xl p-6 max-w-md w-full">
        <h3 class="font-semibold text-lg text-red-600 mb-4">Tolak Pembayaran</h3>
        <form onsubmit="submitReject(event)">
            <input type="hidden" id="reject-booking-id">
            <textarea id="rejection-reason" rows="4" required maxlength="500" class="w-full px-3 py-2 border rounded-lg mb-4" placeholder="Alasan penolakan..."></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2 border rounded-lg">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg">Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Reason Modal -->
<div id="reason-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeReasonModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all animate-in">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-t-2xl p-6 text-white relative">
            <div class="flex items-center pr-8">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Alasan Pembatalan</h3>
                    <p class="text-red-100 text-sm">Detail pembatalan booking</p>
                </div>
            </div>
            <!-- Close button (X) di pojok kanan atas -->
            <button onclick="closeReasonModal()" class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Content -->
        <div class="p-6">
            <!-- Reason Content -->
            <div id="reason-content" class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4 text-gray-800 whitespace-pre-line min-h-[120px] max-h-64 overflow-y-auto custom-scrollbar">
                <div class="flex items-center justify-center py-8">
                    <svg class="animate-spin w-6 h-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-600">Memuat alasan...</span>
                </div>
            </div>
        </div>
        
        <!-- Footer dengan tombol aksi -->
        <div class="bg-gray-50 rounded-b-2xl px-6 py-4">
            <div class="flex gap-3">
                <!-- Tombol Restore (hijau) -->
                <button id="restore-booking-btn" type="button" 
                        class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all duration-200 font-medium">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Restore Booking
                </button>
                
                <!-- Tombol Hapus Permanen (merah) -->
                <button id="permanent-delete-btn" type="button"
                        class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-medium">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Hapus Permanen
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/admin/booking.js') }}"></script>
@endpush