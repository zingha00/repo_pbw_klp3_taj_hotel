@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 mb-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar User
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Detail User</h1>
        <p class="text-gray-600 mt-1">Informasi lengkap dan riwayat booking user</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Profile Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="text-center">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                         class="w-24 h-24 rounded-full mx-auto mb-4 object-cover">
                    <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-600">{{ $user->email }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-2
                        @if($user->is_active) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                        @if($user->is_active) Aktif @else Nonaktif @endif
                    </span>
                </div>
                
                <div class="mt-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Telepon</span>
                        <span class="text-sm text-gray-900">{{ $user->phone ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Role</span>
                        <span class="text-sm text-gray-900 capitalize">{{ $user->role }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Bergabung</span>
                        <span class="text-sm text-gray-900">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Booking</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->bookings->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Booking Selesai</span>
                        <span class="text-sm font-semibold text-green-600">{{ $user->bookings->where('status', 'confirmed')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Booking Dibatalkan</span>
                        <span class="text-sm font-semibold text-red-600">{{ $user->bookings->where('status', 'cancelled')->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Total Pengeluaran</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $user->formatted_total_spent }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi</h3>
                <div class="space-y-3">
                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" 
                                class="w-full px-4 py-2 rounded-lg font-medium transition-colors
                                @if($user->is_active) bg-red-600 text-white hover:bg-red-700 @else bg-green-600 text-white hover:bg-green-700 @endif">
                            @if($user->is_active) Nonaktifkan User @else Aktifkan User @endif
                        </button>
                    </form>
                    
                    @if($user->role === 'user')
                    <form action="{{ route('admin.users.change-role', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="role" value="admin">
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition-colors"
                                onclick="return confirm('Yakin ingin menjadikan user ini sebagai admin?')">
                            Jadikan Admin
                        </button>
                    </form>
                    @endif
                    
                    @if($user->bookings->isEmpty())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors"
                                onclick="return confirm('Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan!')">
                            Hapus User
                        </button>
                    </form>
                    @else
                    <p class="text-sm text-gray-500 text-center">User tidak dapat dihapus karena memiliki riwayat booking</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Booking History -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Booking</h3>
                </div>
                
                @if($user->bookings->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kamar</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($user->bookings->sortByDesc('created_at') as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-mono text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->created_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->room->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->nights }} malam</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->check_in->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->check_out->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $booking->formatted_total }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($booking->status === 'waiting_verification') bg-blue-100 text-blue-800
                                        @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $booking->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="px-6 py-12 text-center text-gray-500">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="font-medium">Belum ada booking</p>
                    <p class="text-sm">User ini belum pernah melakukan booking</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection