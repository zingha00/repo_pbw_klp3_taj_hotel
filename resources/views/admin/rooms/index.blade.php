@extends('layouts.admin')

@section('title', 'Kelola Kamar')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/rooms.css') }}">
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Kelola Kamar</h1>
            <p class="text-gray-600 mt-1">Manage semua kamar hotel</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}"
            class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kamar
        </a>
    </div>

    <!-- ✅ ALERT SUCCESS -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <!-- ✅ ALERT ERROR -->
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <form action="{{ route('admin.rooms.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kamar..."
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <select name="status"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>
                        Tersedia
                    </option>
                    <option value="unavailable" {{ request('status') == 'unavailable' ? 'selected' : '' }}>
                        Tidak Tersedia
                    </option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                        Maintenance
                    </option>
                </select>
            </div>
            <div>
                <select name="sort"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Terbaru</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 font-medium transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.rooms.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Rooms Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kamar
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Kapasitas
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Stok
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <!-- KOLOM KAMAR -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img
                                    src="{{ $room->image 
                                    ? asset('storage/' . $room->image) 
                                    : asset('assets/images/default-room.jpg') }}"
                                    alt="{{ $room->name }}"
                                    class="w-20 h-20 object-cover rounded-lg shadow-sm">

                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $room->name }}</div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $room->size }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- KOLOM HARGA -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">
                                Rp {{ number_format($room->price, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-gray-500">/malam</div>
                        </td>

                        <!-- KOLOM KAPASITAS -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5 text-sm text-gray-700">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="font-medium">{{ $room->capacity }} Orang</span>
                            </div>
                        </td>

                        <!-- KOLOM STOK -->
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $room->stock > 5 ? 'bg-green-100 text-green-800' : ($room->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $room->stock }} Kamar
                            </div>
                        </td>

                        <!-- KOLOM STATUS -->
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if($room->status === 'available') bg-green-100 text-green-800
                                @elseif($room->status === 'unavailable') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                @if($room->status === 'available') Tersedia
                                @elseif($room->status === 'unavailable') Tidak Tersedia
                                @else Maintenance
                                @endif
                            </span>
                        </td>

                        <!-- KOLOM AKSI -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                    class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit Kamar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-600 hover:text-red-800 transition-colors"
                                        onclick="return confirm('Yakin ingin menghapus kamar {{ $room->name }}?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus:\n- Data kamar\n- Semua gambar terkait\n\nLanjutkan?')"
                                        title="Hapus Kamar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <p class="text-lg font-medium mb-1">Tidak ada kamar ditemukan</p>
                            <p class="text-sm text-gray-400 mb-3">Mulai dengan menambahkan kamar pertama Anda</p>
                            <a href="{{ route('admin.rooms.create') }}"
                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Tambah Kamar Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rooms->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $rooms->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto hide alert setelah 5 detik
    setTimeout(() => {
        const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endpush