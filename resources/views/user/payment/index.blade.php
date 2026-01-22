@extends('layouts.user')

@section('title', 'Pembayaran')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/payment.css') }}">
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
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

        <!-- Booking Summary Card -->
        <div class="info-card mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Kode Booking</p>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $booking->booking_code }}</h1>
                </div>
                <span class="status-badge bg-orange-100 text-orange-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8"/>
                    </svg>
                    Menunggu Pembayaran
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="flex items-start gap-3">
                    <div class="icon-box bg-amber-100">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Nama Kamar</p>
                        <p class="font-semibold text-gray-900">{{ $booking->room->name }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="icon-box bg-amber-100">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Nama Tamu</p>
                        <p class="font-semibold text-gray-900">{{ $booking->guest_name }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="icon-box bg-amber-100">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Check-in</p>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in)->locale('id')->isoFormat('dddd, D MMM YYYY') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <div class="icon-box bg-amber-100">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Check-out</p>
                        <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->check_out)->locale('id')->isoFormat('dddd, D MMM YYYY') }}</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-4 flex justify-between items-center">
                <span class="text-gray-600">Total Pembayaran</span>
                <span class="text-3xl font-bold text-amber-600">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <p class="text-xs text-gray-500 text-right mt-1">
                {{ $nights = \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} malam x 1 kamar (termasuk pajak)
            </p>
        </div>

        <!-- Payment Method Section -->
        <div class="info-card mb-6">
            <div class="flex items-center gap-2 mb-6">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h2 class="text-xl font-bold text-gray-900">Metode Pembayaran</h2>
            </div>

            <form action="{{ route('payment.process') }}" method="POST" enctype="multipart/form-data" id="payment-form">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                <!-- Payment Method Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Kategori</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" 
                                class="payment-method-btn active" 
                                id="bank-btn"
                                onclick="selectPaymentMethod('bank')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            Transfer Bank
                        </button>
                        <button type="button" 
                                class="payment-method-btn" 
                                id="ewallet-btn"
                                onclick="selectPaymentMethod('ewallet')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            E-Wallet
                        </button>
                    </div>
                    <input type="hidden" name="payment_method" id="payment_method" value="bank">
                </div>

                <!-- Bank Selection -->
                <div id="bank-section" class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Bank</label>
                    <select id="bank-select" class="bank-select" name="bank_name">
                        <option value="">-- Pilih Bank --</option>
                        <option value="bca">🏦 BCA - Bank Central Asia</option>
                        <option value="mandiri">🏦 Bank Mandiri</option>
                        <option value="bni">🏦 BNI - Bank Negara Indonesia</option>
                        <option value="bri">🏦 BRI - Bank Rakyat Indonesia</option>
                    </select>

                    <div id="bank-info" class="hidden mt-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg border border-amber-200">
                        <p class="text-sm text-gray-600 mb-2">Rekening Transfer:</p>
                        <p class="font-bold text-lg text-gray-900" id="bank-name"></p>
                        <p class="font-mono text-2xl font-bold text-amber-600 my-2" id="bank-account"></p>
                        <p class="text-sm text-gray-700">a.n. <strong>PT Grand Azure Hotel</strong></p>
                    </div>
                </div>

                <!-- E-Wallet Selection -->
                <div id="ewallet-section" class="mb-6 hidden">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih E-Wallet</label>
                    <select id="ewallet-select" class="bank-select" name="ewallet_name">
                        <option value="">-- Pilih E-Wallet --</option>
                        <option value="gopay">📱 GoPay</option>
                        <option value="ovo">📱 OVO</option>
                        <option value="dana">📱 DANA</option>
                        <option value="shopeepay">📱 ShopeePay</option>
                    </select>

                    <div id="ewallet-info" class="hidden mt-4 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg border border-amber-200">
                        <p class="text-sm text-gray-600 mb-2">Nomor E-Wallet:</p>
                        <p class="font-bold text-lg text-gray-900" id="ewallet-name"></p>
                        <p class="font-mono text-2xl font-bold text-amber-600 my-2" id="ewallet-number"></p>
                        <p class="text-sm text-gray-700">a.n. <strong>PT Grand Azure Hotel</strong></p>
                    </div>
                </div>

                <!-- Upload Payment Proof -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        Upload Bukti Pembayaran
                    </label>

                    <div class="upload-area" onclick="document.getElementById('payment-proof').click()">
                        <input type="file"
                            name="payment_proof"
                            id="payment-proof"
                            accept="image/*"
                            class="hidden"
                            onchange="previewImage(event)"
                            required>

                        <div id="upload-placeholder">
                            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-700 font-semibold mb-1">Klik untuk upload bukti transfer</p>
                            <p class="text-sm text-gray-500">Format: JPG, PNG (Max: 5MB)</p>
                        </div>

                        <div id="image-preview" class="hidden">
                            <img src="" alt="Preview" class="max-h-64 mx-auto rounded-lg mb-3 shadow-lg">
                            <p class="text-sm text-gray-600 font-medium" id="file-name"></p>
                            <button type="button"
                                onclick="resetUpload(event)"
                                class="mt-3 text-sm text-red-600 hover:text-red-700 font-medium">
                                🗑️ Hapus & Upload Ulang
                            </button>
                        </div>
                    </div>

                    @error('payment_proof')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    id="submit-btn"
                    class="w-full bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold py-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl text-lg">
                    Konfirmasi Pembayaran
                </button>
            </form>
        </div>

        <!-- Instructions -->
        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 mb-6">
            <div class="flex gap-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-bold mb-2">Petunjuk Pembayaran:</p>
                    <ol class="list-decimal list-inside space-y-1.5 text-blue-700">
                        <li>Transfer sesuai <strong>TOTAL PERSIS</strong></li>
                        <li>Ambil screenshot bukti transfer</li>
                        <li>Upload bukti di form di samping</li>
                        <li>Klik "Konfirmasi Pembayaran"</li>
                        <li>Tunggu verifikasi admin (maks. 1x24 jam)</li>
                    </ol>
                </div>
            </div>
        </div>

        <!-- Timer -->
        <div class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-orange-900">Selesaikan dalam:</p>
                        <p class="text-xs text-orange-600">Pesanan akan dibatalkan jika tidak dibayar</p>
                    </div>
                </div>
                <p class="text-3xl font-bold text-orange-600" id="countdown">10:12</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const bankData = {
        bca: { name: 'BCA - Bank Central Asia', account: '1234567890' },
        mandiri: { name: 'Bank Mandiri', account: '9876543210' },
        bni: { name: 'BNI - Bank Negara Indonesia', account: '1122334455' },
        bri: { name: 'BRI - Bank Rakyat Indonesia', account: '5566778899' }
    };

    const ewalletData = {
        gopay: { name: 'GoPay', number: '0812-3456-7890' },
        ovo: { name: 'OVO', number: '0813-4567-8901' },
        dana: { name: 'DANA', number: '0814-5678-9012' },
        shopeepay: { name: 'ShopeePay', number: '0815-6789-0123' }
    };

    function selectPaymentMethod(method) {
        document.getElementById('payment_method').value = method;
        
        document.getElementById('bank-btn').classList.toggle('active', method === 'bank');
        document.getElementById('ewallet-btn').classList.toggle('active', method === 'ewallet');
        
        document.getElementById('bank-section').classList.toggle('hidden', method !== 'bank');
        document.getElementById('ewallet-section').classList.toggle('hidden', method !== 'ewallet');
    }

    document.getElementById('bank-select')?.addEventListener('change', e => {
        const bank = bankData[e.target.value];
        const infoDiv = document.getElementById('bank-info');
        
        if (bank) {
            document.getElementById('bank-name').textContent = bank.name;
            document.getElementById('bank-account').textContent = bank.account;
            infoDiv.classList.remove('hidden');
        } else {
            infoDiv.classList.add('hidden');
        }
    });

    document.getElementById('ewallet-select')?.addEventListener('change', e => {
        const wallet = ewalletData[e.target.value];
        const infoDiv = document.getElementById('ewallet-info');
        
        if (wallet) {
            document.getElementById('ewallet-name').textContent = wallet.name;
            document.getElementById('ewallet-number').textContent = wallet.number;
            infoDiv.classList.remove('hidden');
        } else {
            infoDiv.classList.add('hidden');
        }
    });

    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 5 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                event.target.value = '';
                return;
            }

            if (!file.type.match('image.*')) {
                alert('File harus berupa gambar!');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('upload-placeholder').classList.add('hidden');
                document.getElementById('image-preview').classList.remove('hidden');
                document.getElementById('image-preview').querySelector('img').src = e.target.result;
                document.getElementById('file-name').textContent = file.name;
            }
            reader.readAsDataURL(file);
        }
    }

    function resetUpload(event) {
        event.stopPropagation();
        document.getElementById('payment-proof').value = '';
        document.getElementById('upload-placeholder').classList.remove('hidden');
        document.getElementById('image-preview').classList.add('hidden');
    }

    document.getElementById('payment-form').addEventListener('submit', function(e) {
        const fileInput = document.getElementById('payment-proof');
        if (!fileInput.files || !fileInput.files[0]) {
            e.preventDefault();
            alert('Harap upload bukti pembayaran!');
            return false;
        }

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="inline-block animate-spin mr-2">⚪</span> Memproses...';
    });

    function startCountdown() {
        const createdAt = new Date('{{ $booking->created_at }}');
        const deadline = new Date(createdAt.getTime() + (24 * 60 * 60 * 1000));

        setInterval(() => {
            const now = new Date();
            const diff = deadline - now;

            if (diff <= 0) {
                window.location.href = '{{ route("reservations.index") }}';
                return;
            }

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            const timerEl = document.getElementById('countdown');
            if (timerEl) {
                timerEl.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
        }, 1000);
    }

    startCountdown();
</script>
@endpush