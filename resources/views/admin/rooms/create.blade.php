@extends('layouts.admin')

@section('title', 'Tambah Kamar Baru')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/rooms.css') }}">
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Kamar
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Tambah Kamar Baru</h1>
        <p class="text-gray-600 mt-1">Lengkapi informasi kamar hotel</p>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kamar *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                   placeholder="e.g., Deluxe Room">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                            <textarea name="description" rows="4" required
                                      class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                      placeholder="Deskripsikan kamar secara detail...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Malam *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                                    <input type="number" name="price" value="{{ old('price') }}" required min="0"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                           placeholder="0">
                                </div>
                                @error('price')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" required
                                        class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                                    <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                </select>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Specifications -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Spesifikasi Kamar</h2>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Kamar *</label>
                            <input type="text" name="size" value="{{ old('size') }}" required
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                   placeholder="e.g., 32 m²">
                            @error('size')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas *</label>
                            <select name="capacity" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                                <option value="">Pilih Kapasitas</option>
                                <option value="1" {{ old('capacity') == 1 ? 'selected' : '' }}>1 Orang</option>
                                <option value="2" {{ old('capacity') == 2 ? 'selected' : '' }}>2 Orang</option>
                                <option value="3" {{ old('capacity') == 3 ? 'selected' : '' }}>3 Orang</option>
                                <option value="4" {{ old('capacity') == 4 ? 'selected' : '' }}>4 Orang</option>
                                <option value="5" {{ old('capacity') == 5 ? 'selected' : '' }}>5+ Orang</option>
                            </select>
                            @error('capacity')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok Kamar *</label>
                            <input type="number" name="stock" value="{{ old('stock', 1) }}" required min="0"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500"
                                   placeholder="Berapa banyak kamar tipe ini tersedia?">
                            <p class="text-sm text-gray-500 mt-1">Contoh: Jika ada 5 kamar Deluxe, masukkan angka 5</p>
                            @error('stock')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- ✅ FACILITIES - FROM DATABASE -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Fasilitas Kamar *</h2>
                    
                    @if(isset($facilities) && $facilities->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @php
                                $oldFacilities = old('facilities', []);
                            @endphp
                            @foreach($facilities as $facility)
                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                       {{ in_array($facility->id, (array)$oldFacilities) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 rounded">
                                <span class="text-sm text-gray-700">{{ $facility->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800 text-sm">
                                ⚠️ Belum ada data fasilitas. Silakan tambahkan fasilitas terlebih dahulu di menu Master Data.
                            </p>
                        </div>
                    @endif
                    
                    @error('facilities')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ✅ MAIN IMAGE + GALLERY IMAGES -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Foto Kamar</h2>
                    
                    <div class="space-y-6">
                        <!-- ✅ MAIN IMAGE (REQUIRED) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gambar Utama * 
                                <span class="text-xs text-gray-500">(Akan tampil di halaman utama)</span>
                            </label>
                            <input type="file" name="image" accept="image/*" id="main-image" required
                                   class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-sm text-gray-500 mt-1">PNG, JPG, WEBP - Maksimal 2MB</p>
                            @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview Main Image -->
                            <div id="main-image-preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview Gambar Utama:</p>
                                <div class="relative w-full h-48 rounded-lg overflow-hidden border-2 border-blue-500">
                                    <img id="main-image-preview-img" src="" alt="Main Preview" class="w-full h-full object-cover">
                                    <div class="absolute top-2 left-2 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                        MAIN IMAGE
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ✅ GALLERY IMAGES (OPTIONAL) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Galeri (Opsional)
                                <span class="text-xs text-gray-500">(Maksimal 5 foto tambahan)</span>
                            </label>
                            <input type="file" name="images[]" multiple accept="image/*" id="gallery-images"
                                   class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                            <p class="text-sm text-gray-500 mt-1">Upload maksimal 5 foto tambahan (PNG, JPG, WEBP - Max. 2MB per file)</p>
                            @error('images.*')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Preview Gallery Images -->
                            <div id="gallery-preview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview Foto Galeri:</p>
                                <div id="gallery-preview-container" class="grid grid-cols-3 gap-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Publikasi</h3>
                    
                    <div class="space-y-3">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Kamar
                        </button>
                        <a href="{{ route('admin.rooms.index') }}" 
                           class="block w-full text-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
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
                            <h3 class="font-semibold text-lg">Tips</h3>
                            <p class="text-sm text-white/80">Panduan pengisian</p>
                        </div>
                    </div>
                    <ul class="space-y-2 text-sm text-white/90">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <strong>Gambar Utama WAJIB diupload</strong>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Upload foto berkualitas tinggi
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Foto galeri opsional (maksimal 5)
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Pilih minimal 1 fasilitas kamar
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // ✅ PREVIEW MAIN IMAGE
    document.getElementById('main-image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('main-image-preview');
        const previewImg = document.getElementById('main-image-preview-img');
        
        if (file) {
            // Check file size
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                this.value = '';
                preview.classList.add('hidden');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImg.src = event.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    });

    // ✅ PREVIEW GALLERY IMAGES
    document.getElementById('gallery-images')?.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const preview = document.getElementById('gallery-preview');
        const container = document.getElementById('gallery-preview-container');
        
        if (files.length > 5) {
            alert('Maksimal 5 foto galeri!');
            this.value = '';
            preview.classList.add('hidden');
            return;
        }
        
        if (files.length > 0) {
            container.innerHTML = '';
            preview.classList.remove('hidden');
            
            files.forEach((file, index) => {
                // Check file size
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar! Maksimal 2MB per file.`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-lg overflow-hidden border-2 border-gray-200 hover:border-blue-500 transition-colors';
                    div.innerHTML = `
                        <img src="${event.target.result}" alt="Gallery ${index + 1}" class="w-full h-full object-cover">
                        <div class="absolute top-2 right-2 bg-gray-800 text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <span class="text-xs font-bold">${index + 1}</span>
                        </div>
                    `;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        } else {
            preview.classList.add('hidden');
        }
    });
</script>
@endpush