@extends('layouts.admin')

@section('title', 'Profil Hotel')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/hotel.css') }}">
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Profil Hotel</h1>
        <p class="text-gray-600 mt-1">Kelola informasi hotel Anda</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Hotel Information Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Hotel</h2>
                </div>
                
                <form action="{{ route('admin.hotel.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <!-- Hotel Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Hotel *</label>
                            <input type="text" name="name" value="{{ old('name', $hotel->name ?? 'Grand Azure') }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                            <textarea name="description" rows="4" required
                                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                      placeholder="Deskripsikan hotel Anda...">{{ old('description', $hotel->description ?? '') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat *</label>
                            <textarea name="address" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                      placeholder="Alamat lengkap hotel">{{ old('address', $hotel->address ?? 'Jl. Sudirman No. 123, Jakarta, Indonesia') }}</textarea>
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" value="{{ old('email', $hotel->email ?? 'info@grandazure.com') }}" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telepon *</label>
                                <input type="tel" name="phone" value="{{ old('phone', $hotel->phone ?? '+62 21 1234 5678') }}" required
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Facebook</label>
                                <input type="url" name="facebook" value="{{ old('facebook', $hotel->facebook ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                       placeholder="https://facebook.com/...">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                                <input type="url" name="instagram" value="{{ old('instagram', $hotel->instagram ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                       placeholder="https://instagram.com/...">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                                <input type="url" name="twitter" value="{{ old('twitter', $hotel->twitter ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                       placeholder="https://twitter.com/...">
                            </div>
                        </div>

                        <!-- Hotel Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo Hotel</label>
                            <div class="flex items-center gap-4">
                                @if(isset($hotel->logo))
                                <img src="{{ asset('storage/' . $hotel->logo) }}" alt="Logo" class="w-20 h-20 object-contain rounded-lg border">
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="logo" accept="image/*" id="logo-upload"
                                           class="w-full px-4 py-3 border border-gray-200 rounded-lg">
                                    <p class="text-sm text-gray-500 mt-1">PNG, JPG (Max. 2MB)</p>
                                </div>
                            </div>
                            @error('logo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Check-in/Check-out Times -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Check-in</label>
                                <input type="time" name="checkin_time" value="{{ old('checkin_time', $hotel->checkin_time ?? '14:00') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Waktu Check-out</label>
                                <input type="time" name="checkout_time" value="{{ old('checkout_time', $hotel->checkout_time ?? '12:00') }}"
                                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Facilities -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fasilitas Hotel</label>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                @php
                                    $facilities = ['WiFi', 'Kolam Renang', 'Gym', 'Restaurant', 'Spa', 'Parking', 'Room Service', 'Laundry', 'Meeting Room'];
                                    $selectedFacilities = old('facilities', $hotel->facilities ?? []);
                                @endphp
                                @foreach($facilities as $facility)
                                <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" name="facilities[]" value="{{ $facility }}"
                                           {{ in_array($facility, (array)$selectedFacilities) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 rounded">
                                    <span class="text-sm text-gray-700">{{ $facility }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Info & Preview -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Kamar</span>
                        <span class="font-semibold text-gray-900">{{ $totalRooms ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Pemesanan Bulan Ini</span>
                        <span class="font-semibold text-gray-900">{{ $monthlyBookings ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Rating Rata-rata</span>
                        <div class="flex items-center gap-1">
                            <span class="font-semibold text-gray-900">{{ number_format($averageRating ?? 0, 1) }}</span>
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Tingkat Hunian</span>
                        <span class="font-semibold text-gray-900">{{ $occupancyRate ?? 0 }}%</span>
                    </div>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-sm p-6 text-white">
                <div class="flex items-center gap-3 mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <div>
                        <h3 class="font-semibold text-lg">{{ $hotel->name ?? 'Grand Azure' }}</h3>
                        <p class="text-sm text-white/80">Hotel Profile</p>
                    </div>
                </div>
                <p class="text-sm text-white/90 mb-4">
                    {{ Str::limit($hotel->description ?? 'Pengalaman menginap mewah dengan pelayanan terbaik', 100) }}
                </p>
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-white/90">{{ Str::limit($hotel->address ?? 'Jakarta, Indonesia', 50) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin/hotel.js') }}"></script>
@endpush