@extends('layouts.admin')

@section('title', 'Laporan')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan</h1>
                <p class="text-gray-600 mt-1">Analisis data dan statistik hotel</p>
            </div>
            <div class="flex gap-3">
                <!-- Export Dropdown -->
                <div class="relative">
                    <button id="exportDropdown" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export Laporan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="exportMenu" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                        <div class="p-4">
                            <h3 class="font-medium text-gray-900 mb-3">Export Laporan</h3>
                            <form id="exportForm" class="space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                                        <input type="date" name="start_date" value="{{ now()->startOfMonth()->format('Y-m-d') }}" 
                                               class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                                        <input type="date" name="end_date" value="{{ now()->endOfMonth()->format('Y-m-d') }}" 
                                               class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Laporan</label>
                                    <select name="type" class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                                        <option value="bookings">Laporan Booking</option>
                                        <option value="revenue">Laporan Revenue</option>
                                        <option value="rooms">Laporan Kamar</option>
                                    </select>
                                </div>
                                <div class="flex gap-2 pt-2">
                                    <button type="button" onclick="exportReport('pdf')" 
                                            class="flex-1 bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded text-xs font-medium flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Export PDF
                                    </button>
                                    <button type="button" onclick="exportReport('excel')" 
                                            class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-xs font-medium flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a4 4 0 01-4-4V5a4 4 0 014-4h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a4 4 0 01-4 4z"/>
                                        </svg>
                                        Export Excel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Booking</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_bookings']) }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Bulan ini: {{ number_format($stats['this_month_bookings']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-900">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Bulan ini: Rp {{ number_format($stats['this_month_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Kamar</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_rooms']) }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Tersedia</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total User</p>
                    <h3 class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Terdaftar</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Revenue Terakhir</h2>
                <div class="flex gap-1 bg-gray-100 rounded-lg p-1">
                    <button onclick="changePeriod('7')" class="period-btn px-3 py-1 text-xs font-medium rounded-md transition-all duration-200 {{ $period == '7' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        7 Hari
                    </button>
                    <button onclick="changePeriod('30')" class="period-btn px-3 py-1 text-xs font-medium rounded-md transition-all duration-200 {{ $period == '30' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        30 Hari
                    </button>
                    <button onclick="changePeriod('60')" class="period-btn px-3 py-1 text-xs font-medium rounded-md transition-all duration-200 {{ $period == '60' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        2 Bulan
                    </button>
                    <button onclick="changePeriod('180')" class="period-btn px-3 py-1 text-xs font-medium rounded-md transition-all duration-200 {{ $period == '180' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        6 Bulan
                    </button>
                    <button onclick="changePeriod('365')" class="period-btn px-3 py-1 text-xs font-medium rounded-md transition-all duration-200 {{ $period == '365' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        1 Tahun
                    </button>
                </div>
            </div>
            <div class="h-64 relative">
                <canvas id="revenueChart"></canvas>
                <div id="chartLoading" class="hidden absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                    <div class="flex items-center gap-2 text-gray-600">
                        <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span class="text-sm">Memuat data...</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Rooms -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Kamar Terpopuler</h2>
            <div class="space-y-4">
                @foreach($topRooms as $room)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <img src="{{ $room->image_url }}" alt="{{ $room->name }}" 
                             class="w-12 h-12 rounded-lg object-cover">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $room->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $room->bookings_count }} booking</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">Rp {{ number_format($room->price, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500">per malam</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Booking Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tamu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kamar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-mono text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->created_at->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $booking->guest_name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->guest_email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
    {{ $booking->room?->name ?? 'Kamar dihapus' }}
</div>
                            <div class="text-xs text-gray-500">{{ $booking->nights }} malam</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $booking->check_in->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $booking->formatted_total }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
    @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
    @elseif($booking->status === 'waiting_verification') bg-blue-100 text-blue-800
    @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
    @elseif($booking->status === 'completed') bg-emerald-100 text-emerald-800
    @else bg-red-100 text-red-800 @endif">
    {{ $booking->status_label ?? '-' }}
</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Export Dropdown Toggle - DIPERBAIKI
document.addEventListener('DOMContentLoaded', function() {
    const exportDropdown = document.getElementById('exportDropdown');
    const exportMenu = document.getElementById('exportMenu');

    if (exportDropdown && exportMenu) {
        exportDropdown.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            exportMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!exportDropdown.contains(e.target) && !exportMenu.contains(e.target)) {
                exportMenu.classList.add('hidden');
            }
        });

        // Prevent menu from closing when clicking inside it
        exportMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});

// Export Report Function - DIPERBAIKI
function exportReport(format) {
    const form = document.getElementById('exportForm');
    const formData = new FormData(form);
    
    const startDate = formData.get('start_date');
    const endDate = formData.get('end_date');
    const type = formData.get('type');
    
    // Validate dates
    if (!startDate || !endDate) {
        alert('Silakan pilih tanggal mulai dan akhir');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        return;
    }
    
    // Build URL
    const baseUrl = format === 'pdf' ? '{{ route("admin.reports.export-pdf") }}' : '{{ route("admin.reports.export-excel") }}';
    const params = new URLSearchParams({
        type: type,
        start_date: startDate,
        end_date: endDate
    });
    
    const url = `${baseUrl}?${params.toString()}`;
    
    // Show loading state
    const button = window.event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="w-3 h-3 animate-spin inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Exporting...';
    button.disabled = true;
    
    // Create temporary link and trigger download
    const link = document.createElement('a');
    link.href = url;
    link.download = '';
    link.style.display = 'none';
    document.body.appendChild(link);
    link.click();
    
    // Reset button after delay
    setTimeout(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        document.body.removeChild(link);
        // Keep menu open after export
        // document.getElementById('exportMenu').classList.add('hidden');
    }, 2000);
}

// Change Period Function
function changePeriod(period) {
    // Show loading state
    const loadingEl = document.getElementById('chartLoading');
    if (loadingEl) {
        loadingEl.classList.remove('hidden');
    }
    
    const buttons = document.querySelectorAll('.period-btn');
    buttons.forEach(btn => {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    });
    
    // Redirect with period parameter
    const url = new URL(window.location);
    url.searchParams.set('period', period);
    window.location.href = url.toString();
}

// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [{
            label: 'Revenue',
            data: {!! json_encode($chartData['data']) !!},
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgb(59, 130, 246)',
            pointBorderColor: 'rgb(59, 130, 246)',
            pointHoverBackgroundColor: 'rgb(37, 99, 235)',
            pointHoverBorderColor: 'rgb(37, 99, 235)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1,
                callbacks: {
                    label: function(context) {
                        return 'Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    maxTicksLimit: {{ $period == '30' ? '10' : '12' }}
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                },
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID', {
                            notation: 'compact',
                            compactDisplay: 'short'
                        }).format(value);
                    }
                }
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        },
        elements: {
            point: {
                radius: 4,
                hoverRadius: 6
            }
        },
        animation: {
            duration: 750,
            easing: 'easeInOutQuart'
        }
    }
});
</script>
@endsection