@extends('layouts.user')

@section('title', 'Reset Password - Grand Azure Hotel')

@section('content')
<section class="min-h-screen bg-gray-50 flex items-center justify-center py-12">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-2xl p-8 shadow-lg">
            <div class="text-center mb-8">
                <svg class="w-12 h-12 gold-accent mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <h1 class="font-display text-2xl font-bold text-gray-900">Reset Password</h1>
                <p class="text-gray-500 mt-2">Masukkan password baru Anda</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                @if($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $email) }}" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg" 
                               placeholder="email@example.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="password" required minlength="8" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg" 
                               placeholder="Min. 8 karakter">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" required 
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg" 
                               placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit" class="btn-primary text-white w-full py-4 rounded-lg font-semibold mt-6">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</section>
@endsection