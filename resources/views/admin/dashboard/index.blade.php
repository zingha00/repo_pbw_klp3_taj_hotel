@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admin/dashboard.css') }}">
<style>
    /* Occupancy bar animation */
    .occupancy-bar {
        width: 0%;
        transition: width 1s ease-in-out;
    }
    
    /* Enhanced card hover effects */
    .stats-card {
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    /* Chart container styling */
    #revenueChart {
        max-height: 256px;
    }
    
    /* Recent bookings hover effect */
    .booking-item {
        transition: all 0.2s ease;
    }
    
    .booking-item:hover {
        background-color: #f8fafc;
        border-left: 4px solid #3b82f6;
        padding-left: 12px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .booking-item .hidden {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-1">Selamat datang kembali, {{ auth()->user()->name }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="stats-card bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($stats['totalRevenue'], 0, ',', '.') }}
                    </h3>
                    <p class="text-sm {{ $stats['revenueGrowth'] >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2 flex items-center">
                        @if($stats['revenueGrowth'] == 0)
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            <span class="font-medium text-gray-500">Tidak ada perubahan</span>
                        @elseif($stats['revenueGrowth'] > 0)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7h-10"/>
                            </svg>
                            <span class="font-medium">+{{ $stats['revenueGrowth'] }}%</span> dari bulan lalu
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"/>
                            </svg>
                            <span class="font-medium">{{ $stats['revenueGrowth'] }}%</span> dari bulan lalu
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="stats-card bg-white rounded-xl p-6 shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pemesanan</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['totalBookings'] }}</h3>
                    <p class="text-sm {{ $stats['bookingGrowth'] >= 0 ? 'text-blue-600' : 'text-red-600' }} mt-2 flex items-center">
                        @if($stats['bookingGrowth'] == 0)
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                            <span class="font-medium text-gray-500">{{ $stats['pendingBookings'] }} menunggu</span>
                        @elseif($stats['bookingGrowth'] > 0)
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7h-10"/>
                            </svg>
                            <span class="font-medium">+{{ $stats['bookingGrowth'] }}%</span> dari bulan lalu
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"/>
                            </svg>
                            <span class="font-medium">{{ $stats['bookingGrowth'] }}%</span> dari bulan lalu
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Rooms -->
        <div class="stats-card bg-white rounded-xl p-6 shadow-sm border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Kamar</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['totalRooms'] }}</h3>
                    <p class="text-sm text-purple-600 mt-2">
                        <span class="font-medium">{{ $stats['availableRooms'] }}</span> tersedia
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="stats-card bg-white rounded-xl p-6 shadow-sm border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Pengguna</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $stats['totalUsers'] }}</h3>
                    <p class="text-sm text-amber-600 mt-2">
                        <span class="font-medium">{{ $stats['newUsersThisMonth'] }}</span> baru bulan ini
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Grafik Pendapatan</h3>
                <select id="chartPeriod" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500">
                    <option value="7">7 Hari Terakhir</option>
                    <option value="30" selected>30 Hari Terakhir</option>
                </select>
            </div>
            <div class="h-64 relative">
                <canvas id="revenueChart"></canvas>
                <div id="chartLoading" class="hidden absolute inset-0 flex items-center justify-center bg-white bg-opacity-75">
                    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Room Occupancy -->
        <div class="bg-white rounded-xl p-6 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Tingkat Hunian</h3>

            <div class="space-y-4">
                @if(isset($roomTypes) && $roomTypes->count() > 0)
                    @foreach ($roomTypes as $type)
                        @php
                            $percentage = min((int) ($type->occupancy ?? 0), 100);
                        @endphp

                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">{{ $type->name ?? 'Unknown' }}</span>
                                <span class="font-medium">{{ $percentage }}%</span>
                            </div>

                            <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                <div
                                    class="occupancy-bar h-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 transition-all duration-500"
                                    data-width="{{ $percentage }}"
                                    role="progressbar"
                                    aria-valuenow="{{ $percentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-gray-500 py-4">
                        <p>Tidak ada data kamar tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Recent Bookings Section - Horizontal Layout -->
    <div class="bg-white rounded-xl shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Pemesanan Terbaru</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-blue-600 hover:underline">
                    Lihat Semua
                </a>
            </div>
        </div>
        
        @forelse($recentBookings as $booking)
        <div class="booking-item p-4 border-b border-gray-100">
    <div class="grid grid-cols-12 gap-3 items-center">
        
        
        
        <!-- User Info - 2 cols (16.66%) -->
        <div class="col-span-2">
            <p class="font-semibold text-gray-900 truncate text-sm">{{ $booking->user_name }}</p>
            <p class="text-xs text-gray-600 truncate">{{ $booking->user_email }}</p>
        </div>
        
        <!-- Room Info - 2 cols (16.66%) -->
        <div class="col-span-2 hidden lg:block">
            <p class="text-sm font-medium text-gray-900 truncate">{{ $booking->room->name ?? 'Kamar tidak tersedia' }}</p>
            <p class="text-xs text-gray-500">{{ $booking->guests }} Tamu</p>
        </div>

        <!-- Dates - 2 cols (16.66%) -->
        <div class="col-span-2 hidden md:block">
            <p class="text-sm text-gray-900 font-medium">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</p>
            <p class="text-xs text-gray-500 font-mono">{{ $booking->booking_code }}</p>
        </div>

        <!-- Price - 2 cols (16.66%) -->
        <div class="col-span-2 text-right">
            <p class="text-sm font-bold text-gray-900">
                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500">
                {{ $booking->created_at->diffForHumans() }}
            </p>
        </div>

        <!-- Status - 3 cols (25%) -->
        <div class="col-span-3 text-right">
            <span class="
                @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
                @elseif($booking->status === 'waiting_verification') bg-blue-100 text-blue-800
                @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
                @elseif($booking->status === 'completed') bg-emerald-100 text-emerald-800
                @else bg-red-100 text-red-800
                @endif
                px-3 py-1.5 rounded-full text-xs font-medium inline-block">
                @if($booking->status === 'pending') Menunggu
                @elseif($booking->status === 'waiting_verification') Verifikasi
                @elseif($booking->status === 'confirmed') Terkonfirmasi
                @elseif($booking->status === 'completed') Selesai
                @else Dibatalkan
                @endif
            </span>
        </div>
        
    </div>
</div>
        @empty
        <div class="p-12 text-center text-gray-500">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="font-medium text-gray-900 mb-1">Belum ada pemesanan</p>
            <p class="text-sm text-gray-500">Pemesanan terbaru akan muncul di sini</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('revenueChart');
        if (!canvas) {
            console.error('[Chart] Canvas #revenueChart tidak ditemukan');
            return;
        }

        /* ===============================
           DATA DARI BACKEND (AMAN)
        =============================== */

        // Data dari controller Laravel
        const chartLabels = @json($chartData['labels'] ?? []);
        const chartValues = @json($chartData['values'] ?? []);

        console.log('[Chart Init]', {
            chartLabels,
            chartValues
        });

        /* ===============================
           FALLBACK DATA (ANTI BLANK)
        =============================== */
        const labels = chartLabels.length ?
            chartLabels :
            ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];

        const values = chartValues.length ?
            chartValues :
            [0, 0, 0, 0, 0, 0, 0];

        /* ===============================
           INIT CHART
        =============================== */
        const revenueChart = new Chart(canvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: values,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.15)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        padding: 12,
                        callbacks: {
                            label: ctx =>
                                'Pendapatan: Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => 'Rp ' + v.toLocaleString('id-ID')
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        /* ===============================
           HANDLE PERIODE FILTER
        =============================== */
        const periodSelect = document.getElementById('chartPeriod');
        const chartLoading = document.getElementById('chartLoading');

        if (!periodSelect) return;

        periodSelect.addEventListener('change', async () => {
            const period = periodSelect.value;

            chartLoading?.classList.remove('hidden');

            try {
                const res = await fetch(`/admin/dashboard/chart-data?period=${period}`);
                if (!res.ok) throw new Error('Fetch gagal');

                const data = await res.json();
                console.log('[Chart Update]', data);

                revenueChart.data.labels = data.data.labels ?? [];
                revenueChart.data.datasets[0].data = data.data.values ?? [];
                revenueChart.update();

            } catch (err) {
                console.error('[Chart Error]', err);
                alert('Gagal memuat data grafik.');
            } finally {
                setTimeout(() => chartLoading?.classList.add('hidden'), 300);
            }
        });

        /* ===============================
           ANIMATE OCCUPANCY BARS
        =============================== */
        setTimeout(() => {
            document.querySelectorAll('.occupancy-bar').forEach(bar => {
                const width = bar.getAttribute('data-width');
                bar.style.width = width + '%';
            });
        }, 500);
    });
</script>

@endpush