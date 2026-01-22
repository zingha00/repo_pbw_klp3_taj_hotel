@extends('layouts.admin')

@section('title', 'Kelola Pembayaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/payment.css') }}">
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Kelola Pembayaran</h1>
        <p class="text-gray-600 mt-1">Verifikasi dan kelola pembayaran</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 animate-fade-in">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 animate-fade-in">
        {{ session('error') }}
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Menunggu Verifikasi</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($stats['pending_amount'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Terverifikasi</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['verified'] }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Rp {{ number_format($stats['verified_amount'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Bulan ini</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kode booking..." 
                       class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div>
                <select name="method" class="w-full px-4 py-2 border border-gray-200 rounded-lg">
                    <option value="">Semua Metode</option>
                    <option value="bank" {{ request('method') == 'bank' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="ewallet" {{ request('method') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Filter
                </button>
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tamu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-mono text-sm font-medium text-gray-900">{{ $payment->booking->booking_code }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->booking->room->name }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $payment->booking->guest_name }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->booking->guest_email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                @if($payment->payment_method === 'bank')
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <span class="text-sm">Transfer Bank</span>
                                @else
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm">E-Wallet</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="
                                @if($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($payment->status === 'verified') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif
                                px-3 py-1 rounded-full text-xs font-medium">
                                @if($payment->status === 'pending') Menunggu
                                @elseif($payment->status === 'verified') Terverifikasi
                                @else Ditolak
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button type="button"
                                    data-payment='@json($payment)'
                                    onclick="openDetailModal(this)"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium transition">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="font-medium">Tidak ada pembayaran</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Detail Modal -->
<div id="detail-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70">
    <div class="relative bg-white rounded-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Close Button -->
        <button onclick="closeDetailModal()" 
                class="absolute top-4 right-4 z-10 text-gray-400 hover:text-gray-600 bg-white rounded-full p-2 shadow-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
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
                <button onclick="closeDetailModal()" class="flex-1 px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition">
                    Tutup
                </button>
                <button onclick="openRejectModalFromDetail()" id="reject-button" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition shadow-sm">
                    Tolak
                </button>
                <button onclick="confirmPayment()" id="confirm-button" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition shadow-sm">
                    Konfirmasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tolak Pembayaran</h3>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                <textarea name="reason" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                          placeholder="Masukkan alasan penolakan..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium">
                    Tolak Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentPaymentId = null;

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Payment page loaded');
    });

    function openDetailModal(button) {
        try {
            const dataPayment = button.getAttribute('data-payment');
            console.log('Raw data-payment:', dataPayment);
            
            const payment = JSON.parse(dataPayment);
            console.log('Parsed payment:', payment);
            
            currentPaymentId = payment.id;
            
            // Validasi data booking
            if (!payment.booking) {
                console.error('Booking data not found');
                alert('Data booking tidak ditemukan');
                return;
            }
            
            // Set booking details
            document.getElementById('detail-booking-code').textContent = payment.booking.booking_code || '-';
            document.getElementById('detail-room').textContent = (payment.booking.room && payment.booking.room.name) ? payment.booking.room.name : '-';
            document.getElementById('detail-guest-name').textContent = payment.booking.guest_name || '-';
            document.getElementById('detail-guest-email').textContent = payment.booking.guest_email || '-';
            
            // Format dates
            if (payment.booking.check_in) {
                try {
                    const checkinDate = new Date(payment.booking.check_in);
                    document.getElementById('detail-checkin').textContent = checkinDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                } catch (e) {
                    console.error('Error formatting check-in date:', e);
                    document.getElementById('detail-checkin').textContent = payment.booking.check_in;
                }
            } else {
                document.getElementById('detail-checkin').textContent = '-';
            }
            
            if (payment.booking.check_out) {
                try {
                    const checkoutDate = new Date(payment.booking.check_out);
                    document.getElementById('detail-checkout').textContent = checkoutDate.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                } catch (e) {
                    console.error('Error formatting check-out date:', e);
                    document.getElementById('detail-checkout').textContent = payment.booking.check_out;
                }
            } else {
                document.getElementById('detail-checkout').textContent = '-';
            }
            
            // Guests count
            document.getElementById('detail-guests').textContent = payment.booking.guests ? (payment.booking.guests + ' orang') : '-';
            
            // Total amount
            try {
                const formattedAmount = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(payment.amount || 0);
                document.getElementById('detail-total').textContent = formattedAmount;
            } catch (e) {
                console.error('Error formatting amount:', e);
                document.getElementById('detail-total').textContent = 'Rp ' + (payment.amount || 0);
            }
            
            // Payment proof image
            if (payment.payment_proof) {
                document.getElementById('detail-proof-image').src = `/storage/${payment.payment_proof}`;
            } else {
                document.getElementById('detail-proof-image').src = '/images/no-image.png';
                document.getElementById('detail-proof-image').alt = 'Tidak ada bukti';
            }
            
            // Show/hide action buttons based on status
            const rejectButton = document.getElementById('reject-button');
            const confirmButton = document.getElementById('confirm-button');
            
            console.log('Payment status:', payment.status);
            console.log('Reject button:', rejectButton);
            console.log('Confirm button:', confirmButton);
            
            if (payment.status === 'pending') {
                console.log('Status is pending - showing buttons');
                if (rejectButton) rejectButton.style.display = 'block';
                if (confirmButton) confirmButton.style.display = 'block';
            } else {
                console.log('Status is not pending - hiding buttons');
                if (rejectButton) rejectButton.style.display = 'none';
                if (confirmButton) confirmButton.style.display = 'none';
            }
            
            // Show modal
            document.getElementById('detail-modal').classList.remove('hidden');
            console.log('Modal opened successfully');
            
        } catch (error) {
            console.error('Error details:', error);
            console.error('Error stack:', error.stack);
            alert('Gagal membuka detail pembayaran: ' + error.message);
        }
    }

    function closeDetailModal() {
        document.getElementById('detail-modal').classList.add('hidden');
        currentPaymentId = null;
    }

    function confirmPayment() {
        if (!currentPaymentId) {
            alert('Payment ID tidak ditemukan');
            return;
        }
        
        if (confirm('Yakin ingin memverifikasi pembayaran ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/payments/${currentPaymentId}/verify`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }

    function openRejectModalFromDetail() {
        if (!currentPaymentId) {
            alert('Payment ID tidak ditemukan');
            return;
        }
        
        const form = document.getElementById('rejectForm');
        form.action = `/admin/payments/${currentPaymentId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeRejectModal();
        }
    });

    // Close modal when clicking outside
    document.getElementById('detail-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });
</script>
@endpush