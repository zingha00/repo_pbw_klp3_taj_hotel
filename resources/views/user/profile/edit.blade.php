@extends('layouts.user')

@section('title', 'Edit Profil')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/user/profile.css') }}">
@endpush

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6">
        <a href="{{ route('profile.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Profil
        </a>
    </div>

    <h1 class="font-display text-3xl font-bold text-gray-900 mb-8">Edit Profil</h1>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-xl p-6 shadow-sm">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Avatar -->
            <div class="mb-6 text-center">
                <div class="relative inline-block">
                    <div class="w-32 h-32 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                        @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Profile" class="w-full h-full object-cover" id="avatarPreview">
                        @else
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        @endif
                    </div>
                    <label for="avatar" class="absolute bottom-0 right-0 bg-amber-600 text-white p-2 rounded-full cursor-pointer hover:bg-amber-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
                </div>
                @error('avatar')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-sm mt-2">JPG, PNG (Max. 2MB)</p>
            </div>

            <!-- Personal Information -->
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-amber-600">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-amber-600">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                           class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-amber-600"
                           placeholder="+62">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 mt-6 pt-6 border-t">
                <button type="submit" 
                        class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-semibold flex-1 transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('profile.index') }}" 
                   class="border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 text-center transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/user/profile.js') }}"></script>
<script>
    // Preview avatar before upload
    document.getElementById('avatar')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const container = document.querySelector('.w-32.h-32');
                
                // Check if img already exists
                let img = document.getElementById('avatarPreview');
                if (img) {
                    // Update existing image
                    img.src = event.target.result;
                } else {
                    // Create new image element
                    container.innerHTML = `<img src="${event.target.result}" alt="Preview" class="w-full h-full object-cover" id="avatarPreview">`;
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush