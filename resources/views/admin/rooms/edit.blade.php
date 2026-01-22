@extends('layouts.admin')

@section('title', 'Edit Kamar')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/rooms.css') }}">
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Kamar
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Edit Kamar: {{ $room->name }}</h1>
        <p class="text-gray-600 mt-1">Perbarui informasi kamar hotel</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('admin.rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kamar *</label>
                            <input type="text" name="name" value="{{ old('name', $room->name) }}" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi *</label>
                            <textarea name="description" rows="4" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Malam *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                                    <input type="number" name="price" value="{{ old('price', $room->price) }}" required min="0"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                                </div>
                                @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                                    <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Tersedia</option>
                                    <option value="unavailable" {{ old('status', $room->status) == 'unavailable' ? 'selected' : '' }}>Tidak Tersedia</option>
                                    <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                            <input type="text" name="size" value="{{ old('size', $room->size) }}" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                            @error('size')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas *</label>
                            <select name="capacity" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('capacity', $room->capacity) == $i ? 'selected' : '' }}>
                                    {{ $i }} Orang{{ $i == 5 ? '+' : '' }}
                                    </option>
                                @endfor
                            </select>
                            @error('capacity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok Kamar *</label>
                            <input type="number" name="stock" value="{{ old('stock', $room->stock) }}" required min="0"
                                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
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
                                // ✅ Get selected facility IDs from room's facilities relation
                                $selectedFacilities = old('facilities', $room->facilities->pluck('id')->toArray());
                            @endphp
                            @foreach($facilities as $facility)
                            <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors {{ in_array($facility->id, $selectedFacilities) ? 'bg-blue-50 border-blue-500' : '' }}">
                                <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                    {{ in_array($facility->id, $selectedFacilities) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 rounded">
                                <span class="text-sm text-gray-700">{{ $facility->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-yellow-800 text-sm">
                                ⚠️ Belum ada data fasilitas di database.
                            </p>
                        </div>
                    @endif

                    @error('facilities')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ✅ IMAGES SECTION - WITH RELATION TO room_images TABLE -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Foto Kamar</h2>

                    <!-- ✅ CURRENT MAIN IMAGE -->
                    @if($room->image)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama Saat Ini</label>
                        <div class="relative w-full h-64 rounded-lg overflow-hidden border-2 border-blue-500 group">
                            <img src="{{ asset('storage/' . $room->image) }}" alt="Main Image" class="w-full h-full object-cover">
                            <div class="absolute top-2 left-2 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                MAIN IMAGE
                            </div>
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                <label class="bg-white text-gray-700 px-4 py-2 rounded-lg cursor-pointer hover:bg-gray-100 transition-colors">
                                    <input type="checkbox" name="remove_main_image" value="1" class="hidden" onchange="this.parentElement.classList.toggle('bg-red-600'); this.parentElement.classList.toggle('text-white');">
                                    <span>Hapus Gambar Utama</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- ✅ UPLOAD NEW MAIN IMAGE -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $room->image ? 'Ganti Gambar Utama' : 'Upload Gambar Utama *' }}
                        </label>
                        <input type="file" name="image" accept="image/*" id="main-image" {{ !$room->image ? 'required' : '' }}
                            class="w-full px-4 py-3 border-2 border-blue-200 rounded-lg focus:outline-none focus:border-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-sm text-gray-500 mt-1">PNG, JPG, WEBP - Maksimal 2MB</p>

                        <!-- Preview New Main Image -->
                        <div id="main-image-preview" class="mt-3 hidden">
                            <div class="relative w-full h-48 rounded-lg overflow-hidden border-2 border-green-500">
                                <img id="main-image-preview-img" src="" alt="New Main Preview" class="w-full h-full object-cover">
                                <div class="absolute top-2 left-2 bg-green-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    NEW MAIN IMAGE
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-6">

                    <!-- ✅ CURRENT GALLERY IMAGES - FROM room_images RELATION -->
                    @if($room->roomImages->count() > 0)
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Foto Galeri Saat Ini ({{ $room->roomImages->count() }} foto)
    </label>

    <div class="grid grid-cols-3 gap-3">
        @foreach($room->roomImages as $image)
            <div class="relative aspect-square rounded-lg overflow-hidden border-2 border-gray-200 group">
                <img src="{{ asset('storage/' . $image->image_path) }}"
                     alt="Gallery {{ $image->order }}"
                     class="w-full h-full object-cover">

                <div class="absolute top-2 right-2 bg-gray-800 text-white rounded-full w-6 h-6 flex items-center justify-center">
                    <span class="text-xs font-bold">{{ $image->order }}</span>
                </div>

                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <label class="bg-red-600 text-white px-3 py-1 rounded cursor-pointer hover:bg-red-700 transition-colors">
                        <input type="checkbox"
                               name="remove_images[]"
                               value="{{ $image->id }}"
                               class="hidden"
                               onchange="toggleDeleteButton(this);">
                        <span class="delete-text">Tandai Hapus</span>
                    </label>
                </div>
            </div>
        @endforeach
    </div>

    <p class="text-sm text-gray-500 mt-2">
        💡 Hover pada gambar dan klik "Tandai Hapus" untuk menghapus foto tertentu
    </p>
</div>
@else

                    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <p class="text-gray-600 text-sm">📷 Belum ada foto galeri. Upload foto di bawah ini.</p>
                    </div>
                    @endif

                    <!-- ✅ UPLOAD NEW GALLERY IMAGES -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Foto Galeri Baru</label>
                        <input type="file" name="images[]" multiple accept="image/*" id="gallery-images"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
                        <p class="text-sm text-gray-500 mt-1">Maksimal 5 foto tambahan (PNG, JPG, WEBP - Max. 2MB per file)</p>

                        <!-- Preview New Gallery Images -->
                        <div id="gallery-preview" class="mt-3 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Preview Foto Baru:</p>
                            <div id="gallery-preview-container" class="grid grid-cols-3 gap-3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Aksi</h3>

                    <div class="space-y-3">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Kamar
                        </button>
                        <a href="{{ route('admin.rooms.index') }}"
                            class="block w-full text-center border-2 border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                            Batal
                        </a>

                        <button
                            type="button"
                            data-room-id="{{ $room->id }}"
                            class="btn-delete-room w-full bg-red-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-700 transition-colors">
                            Hapus Kamar
                        </button>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="font-semibold text-gray-900 mb-4">Statistik</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Pemesanan</span>
                            <span class="font-semibold text-gray-900">{{ $room->bookings_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Rating</span>
                            <div class="flex items-center gap-1">
                                <span class="font-semibold text-gray-900">{{ number_format($room->rating ?? 0, 1) }}</span>
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Ulasan</span>
                            <span class="font-semibold text-gray-900">{{ $room->reviews_count ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Views</span>
                            <span class="font-semibold text-gray-900">{{ $room->views ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Delete Modal -->
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-xl p-6 max-w-md mx-4 shadow-2xl">
        <h3 class="font-semibold text-lg mb-2">Hapus Kamar</h3>
        <p class="text-gray-500 mb-6">Apakah Anda yakin ingin menghapus kamar ini? Tindakan ini tidak dapat dibatalkan.</p>

        <form id="delete-form" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg font-medium hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ✅ Toggle delete button style
    function toggleDeleteButton(checkbox) {
        const label = checkbox.parentElement;
        const text = label.querySelector('.delete-text');
        
        if (checkbox.checked) {
            label.classList.remove('bg-red-600', 'hover:bg-red-700');
            label.classList.add('bg-green-600', 'hover:bg-green-700');
            text.textContent = '✓ Akan Dihapus';
        } else {
            label.classList.remove('bg-green-600', 'hover:bg-green-700');
            label.classList.add('bg-red-600', 'hover:bg-red-700');
            text.textContent = 'Tandai Hapus';
        }
    }

    // Delete button handler
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButton = document.querySelector('.btn-delete-room');
        if (deleteButton) {
            deleteButton.addEventListener('click', function() {
                const roomId = this.getAttribute('data-room-id');
                deleteRoom(roomId);
            });
        }
    });

    // ✅ PREVIEW NEW MAIN IMAGE
    document.getElementById('main-image')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('main-image-preview');
        const previewImg = document.getElementById('main-image-preview-img');

        if (file) {
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

    // ✅ PREVIEW NEW GALLERY IMAGES
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
                if (file.size > 2 * 1024 * 1024) {
                    alert(`File ${file.name} terlalu besar! Maksimal 2MB per file.`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const div = document.createElement('div');
                    div.className = 'relative aspect-square rounded-lg overflow-hidden border-2 border-green-500';
                    div.innerHTML = `
                        <img src="${event.target.result}" alt="New ${index + 1}" class="w-full h-full object-cover">
                        <div class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 rounded text-xs font-bold">
                            NEW
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

    function deleteRoom(roomId) {
        const modal = document.getElementById('delete-modal');
        const form = document.getElementById('delete-form');
        form.action = `/admin/rooms/${roomId}`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
@endpush