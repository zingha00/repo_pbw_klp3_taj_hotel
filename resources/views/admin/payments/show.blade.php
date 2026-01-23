@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Pembayaran
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail Pembayaran</h1>
        <p class="text-gray-600 mt-1">Informasi lengkap pembayaran booking</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Details -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pembayaran</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kode Booking</label>
                        <p class="text-gray-900 font-mono">{{ $payment->booking->booking_code }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembayaran</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($payment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($payment->status === 'verified') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ $payment->status_label }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pembayaran</label>
                        <p class="text-gray-900 font-semibold">{{ $payment->formatted_amount }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <p class="text-gray-900">{{ $payment->payment_method_label }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembayaran</label>
                        <p class="text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Verifikasi</label>
                        <p class="text-gray-900">{{ $payment->verified_at ? $payment->verified_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                </div>

                @if($payment->rejection_reason)
                <div class="mt-4 p-4 bg-red-50 rounded-lg">
                    <h3 class="text-sm font-medium text-red-800 mb-1">Alasan Penolakan</h3>
                    <p class="text-red-700">{{ $payment->rejection_reason }}</p>
                </div>
                @endif
            </div>

            <!-- Booking Details -->
            <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Detail Booking</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tamu</label>
                        <p class="text-gray-900">{{ $payment->booking->guest_name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-gray-900">{{ $payment->booking->guest_email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <p class="text-gray-900">{{ $payment->booking->guest_phone }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kamar</label>
                        <p class="text-gray-900">{{ $payment->booking->room->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Check-in</label>
                        <p class="text-gray-900">{{ $payment->booking->check_in->format('d M Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Check-out</label>
                        <p class="text-gray-900">{{ $payment->booking->check_out->format('d M Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tamu</label>
                        <p class="text-gray-900">{{ $payment->booking->guests }} orang</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Malam</label>
                        <p class="text-gray-900">{{ $payment->booking->nights }} malam</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions & Proof -->
        <div class="space-y-6">
            <!-- Payment Proof -->
            @if($payment->payment_proof)
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Bukti Pembayaran
                </h2>

                <div class="text-center">
                    <img
                        src="{{ asset('storage/' . $payment->payment_proof) }}"
                        alt="Bukti Pembayaran"
                        class="w-full rounded-lg shadow-sm cursor-pointer"
                        data-image="{{ asset('storage/' . $payment->payment_proof) }}"
                        onclick="openImageModal(this.dataset.image)">

                    <p class="text-sm text-gray-500 mt-2">
                        Klik untuk memperbesar
                    </p>
                </div>
            </div>
            @else
            <p class="text-gray-500 italic">
                Belum ada bukti pembayaran
            </p>
            @endif

            <!-- Actions -->
            @if($payment->status === 'pending')
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h2>
                <div class="space-y-3">
                    <form action="{{ route('admin.payments.verify', $payment) }}" method="POST" class="w-full">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium transition-colors"
                            onclick="return confirm('Yakin ingin memverifikasi pembayaran ini?')">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Verifikasi Pembayaran
                        </button>
                    </form>

                    <button onclick="openRejectModal()"
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Tolak Pembayaran
                    </button>
                </div>
            </div>
            @endif

            <!-- Delete Action -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Hapus Data</h2>
                <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 font-medium transition-colors"
                        onclick="return confirm('Yakin ingin menghapus data pembayaran ini? Tindakan ini tidak dapat dibatalkan!')">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus Data Pembayaran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <img id="modalImage" src="" alt="Bukti Pembayaran" class="max-w-full max-h-full rounded-lg">
        <button onclick="closeImageModal()"
            class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tolak Pembayaran</h3>
        <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
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

<script>
    function openImageModal(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
</script>
@endsection