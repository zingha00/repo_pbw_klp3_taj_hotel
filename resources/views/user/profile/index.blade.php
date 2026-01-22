@extends('layouts.user')

@section('title', 'Profil Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/profile.css') }}">
@endpush

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="font-display text-3xl font-bold text-gray-900 mb-8">Profil Saya</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center gap-3">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Profile Information -->
    <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
        <div class="flex items-center gap-6 mb-6 pb-6 border-b">
            <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                @if(auth()->user()->avatar)
                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile" class="w-full h-full object-cover">
                @else
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                @endif
            </div>
            <div>
                <h2 class="text-xl font-semibold">{{ auth()->user()->name }}</h2>
                <p class="text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>

        <!-- Profile Details - READ ONLY -->
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">
                    {{ auth()->user()->name }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">
                    {{ auth()->user()->email }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                <div class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">
                    {{ auth()->user()->phone ?? '-' }}
                </div>
            </div>
        </div>

        <!-- Tombol Edit Profil - Redirect ke /profile/edit -->
        <a href="{{ route('profile.edit') }}" 
           class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold mt-6 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Profil
        </a>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <h3 class="font-semibold text-lg mb-4">Ganti Password</h3>

        <form action="{{ route('profile.change-password') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Lama</label>
                    <input type="password" name="current_password" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-amber-600">
                    @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password" minlength="8" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-amber-600">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-amber-600">
                </div>
            </div>

            <button type="submit" 
                    class="border-2 border-gray-900 text-gray-900 px-6 py-3 rounded-lg font-semibold mt-6 hover:bg-gray-900 hover:text-white transition-colors">
                Ganti Password
            </button>
        </form>
    </div>

    <!-- Account Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="bg-white rounded-xl p-4 shadow-sm text-center">
            <p class="text-2xl font-bold gold-accent">{{ $totalBookings }}</p>
            <p class="text-gray-500 text-sm">Total Pesanan</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm text-center">
            <p class="text-2xl font-bold text-green-600">{{ $completedBookings }}</p>
            <p class="text-gray-500 text-sm">Selesai</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $reviewsGiven }}</p>
            <p class="text-gray-500 text-sm">Ulasan Diberikan</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/user/profile.js') }}"></script>
@endpush