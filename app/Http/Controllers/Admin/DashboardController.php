<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard utama dengan data real-time
     */
    public function index()
    {
        try {
            // ===============================
            // REAL-TIME STATISTICS
            // ===============================
            $stats = $this->getDashboardStats();

            // ===============================
            // GRAFIK PENDAPATAN (DEFAULT 30 HARI)
            // ===============================
            $chartData = $this->getChartDataByPeriod(30);

            // ===============================
            // ROOM OCCUPANCY REAL-TIME
            // ===============================
            $roomOccupancy = $this->getRoomOccupancyData();

            // ===============================
            // RECENT ACTIVITIES
            // ===============================
            $recentBookings = $this->getRecentBookings();
            $recentPayments = $this->getRecentPayments();

            // ===============================
            // PERFORMANCE METRICS
            // ===============================
            $performanceMetrics = $this->getPerformanceMetrics();

            // Ensure all variables are not null
            $roomTypes = $roomOccupancy ?? collect();
            $chartLabels = $chartData['labels'] ?? [];
            $chartValues = $chartData['values'] ?? [];

            return view('admin.dashboard.index', [
                'stats' => $stats,
                'chartData' => $chartData,
                'roomOccupancy' => $roomOccupancy,
                'roomTypes' => $roomTypes, // Explicit variable for view
                'recentBookings' => $recentBookings,
                'recentPayments' => $recentPayments,
                'performanceMetrics' => $performanceMetrics,
                'chartLabels' => $chartLabels,
                'chartValues' => $chartValues
            ]);
        } catch (\Exception $e) {
            // Log error and return with empty data
            Log::error('Dashboard error: ' . $e->getMessage());

            
            return view('admin.dashboard.index', [
                'stats' => [],
                'chartData' => ['labels' => [], 'values' => []],
                'roomOccupancy' => collect(),
                'roomTypes' => collect(),
                'recentBookings' => collect(),
                'recentPayments' => collect(),
                'performanceMetrics' => [],
                'chartLabels' => [],
                'chartValues' => []
            ]);
        }
    }

    /**
     * Get dashboard statistics dengan caching untuk performa
     */
    private function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', 300, function () { // Cache 5 menit
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();
            $lastMonth = Carbon::now()->subMonth()->startOfMonth();

            // Total statistics
            $totalBookings = Booking::count();
            $totalRevenue = Payment::where('status', 'verified')->sum('amount');
            $totalRooms = Room::count();
            $totalUsers = User::where('role', 'user')->count();

            // Today's statistics
            $todayBookings = Booking::whereDate('created_at', $today)->count();
            $todayRevenue = Payment::where('status', 'verified')
                ->whereDate('created_at', $today)
                ->sum('amount');

            // This month statistics
            $monthlyBookings = Booking::where('created_at', '>=', $thisMonth)->count();
            $monthlyRevenue = Payment::where('status', 'verified')
                ->where('created_at', '>=', $thisMonth)
                ->sum('amount');

            // Last month for comparison
            $lastMonthBookings = Booking::where('created_at', '>=', $lastMonth)
                ->where('created_at', '<', $thisMonth)
                ->count();
            $lastMonthRevenue = Payment::where('status', 'verified')
                ->where('created_at', '>=', $lastMonth)
                ->where('created_at', '<', $thisMonth)
                ->sum('amount');

            // Pending items
            $pendingBookings = Booking::where('status', 'pending')->count();
            $waitingVerification = Booking::where('status', 'waiting_verification')->count();
            $pendingPayments = Payment::where('status', 'pending')->count();

            // Room statistics
            $availableRooms = Room::where('status', 'available')->count();
            $occupiedRooms = $this->getOccupiedRoomsCount();
            $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

            // User statistics
            $activeUsers = User::where('role', 'user')->where('is_active', true)->count();
            $newUsersThisMonth = User::where('role', 'user')
                ->where('created_at', '>=', $thisMonth)
                ->count();

            // Growth calculations
            $bookingGrowth = $lastMonthBookings > 0 
                ? round((($monthlyBookings - $lastMonthBookings) / $lastMonthBookings) * 100, 1) 
                : ($monthlyBookings > 0 ? 100 : 0);
            $revenueGrowth = $lastMonthRevenue > 0 
                ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
                : ($monthlyRevenue > 0 ? 100 : 0);

            return [
                // Totals
                'totalBookings' => $totalBookings,
                'totalRevenue' => $totalRevenue,
                'totalRooms' => $totalRooms,
                'totalUsers' => $totalUsers,
                
                // Today
                'todayBookings' => $todayBookings,
                'todayRevenue' => $todayRevenue,
                
                // Monthly
                'monthlyBookings' => $monthlyBookings,
                'monthlyRevenue' => $monthlyRevenue,
                
                // Pending
                'pendingBookings' => $pendingBookings,
                'waitingVerification' => $waitingVerification,
                'pendingPayments' => $pendingPayments,
                
                // Rooms
                'availableRooms' => $availableRooms,
                'occupiedRooms' => $occupiedRooms,
                'occupancyRate' => $occupancyRate,
                
                // Users
                'activeUsers' => $activeUsers,
                'newUsersThisMonth' => $newUsersThisMonth,
                
                // Growth
                'bookingGrowth' => $bookingGrowth,
                'revenueGrowth' => $revenueGrowth,
                
                // Formatted values
                'formattedTotalRevenue' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                'formattedTodayRevenue' => 'Rp ' . number_format($todayRevenue, 0, ',', '.'),
                'formattedMonthlyRevenue' => 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'),
            ];
        });
    }

    /**
     * Get room occupancy data dengan detail per kamar
     */
    private function getRoomOccupancyData()
    {
        return Cache::remember('room_occupancy', 300, function () {
            $rooms = Room::select('id', 'name', 'stock', 'status')
                ->where('status', '!=', 'maintenance')
                ->get();

            if ($rooms->isEmpty()) {
                return collect(); // Return empty collection if no rooms
            }

            return $rooms->map(function ($room) {
                // Hitung kamar yang sedang terisi hari ini
                $occupiedToday = Booking::where('room_id', $room->id)
                    ->whereIn('status', ['confirmed', 'completed'])
                    ->whereDate('check_in', '<=', today())
                    ->whereDate('check_out', '>=', today())
                    ->sum('rooms_count');

                // Hitung booking untuk 7 hari ke depan
                $upcomingBookings = Booking::where('room_id', $room->id)
                    ->whereIn('status', ['confirmed', 'waiting_verification'])
                    ->whereBetween('check_in', [today(), today()->addDays(7)])
                    ->count();

                $occupancyRate = $room->stock > 0 
                    ? round(($occupiedToday / $room->stock) * 100, 1) 
                    : 0;

                // Format untuk view compatibility
                $room->occupancy = min($occupancyRate, 100);
                $room->occupied = $occupiedToday;
                $room->available = max(0, $room->stock - $occupiedToday);
                $room->occupancy_rate = $occupancyRate;
                $room->upcoming_bookings = $upcomingBookings;
                $room->status_color = $this->getRoomStatusColor($room->status, $occupancyRate);

                return $room;
            });
        });
    }

    /**
     * Get recent bookings dengan informasi lengkap
     */
    private function getRecentBookings()
    {
        return Booking::with(['room:id,name', 'payment:id,booking_id,status,amount'])
            ->select('id', 'booking_code', 'room_id', 'guest_name', 'guest_email', 
                    'check_in', 'check_out', 'total_price', 'status', 'created_at')
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($booking) {
                // Format untuk view compatibility
                $booking->user_name = $booking->guest_name;
                $booking->formatted_total = 'Rp ' . number_format($booking->total_price, 0, ',', '.');
                $booking->status_label = $this->getBookingStatusLabel($booking->status);
                $booking->status_color = $this->getBookingStatusColor($booking->status);
                $booking->payment_status = $booking->payment->status ?? 'pending';
                $booking->days_until_checkin = $booking->check_in->diffInDays(today(), false);
                
                return $booking;
            });
    }

    /**
     * Get recent payments dengan informasi lengkap
     */
    private function getRecentPayments()
    {
        return Payment::with(['booking:id,booking_code,guest_name,room_id', 'booking.room:id,name'])
            ->select('id', 'booking_id', 'amount', 'payment_method', 'status', 
                    'payment_proof', 'created_at', 'verified_at')
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'booking_code' => $payment->booking->booking_code ?? 'N/A',
                    'guest_name' => $payment->booking->guest_name ?? 'N/A',
                    'room_name' => $payment->booking->room->name ?? 'N/A',
                    'amount' => $payment->amount,
                    'formatted_amount' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                    'payment_method' => $payment->payment_method,
                    'payment_method_label' => $this->getPaymentMethodLabel($payment->payment_method),
                    'status' => $payment->status,
                    'status_label' => $this->getPaymentStatusLabel($payment->status),
                    'status_color' => $this->getPaymentStatusColor($payment->status),
                    'has_proof' => !empty($payment->payment_proof),
                    'created_at' => $payment->created_at->diffForHumans(),
                    'verified_at' => $payment->verified_at ? $payment->verified_at->diffForHumans() : null
                ];
            });
    }

    /**
     * Get performance metrics untuk dashboard
     */
    private function getPerformanceMetrics()
    {
        return Cache::remember('performance_metrics', 600, function () { // Cache 10 menit
            $last7Days = Carbon::now()->subDays(7);
            $last30Days = Carbon::now()->subDays(30);

            // Conversion rate (booking confirmed vs total booking)
            $totalBookings7Days = Booking::where('created_at', '>=', $last7Days)->count();
            $confirmedBookings7Days = Booking::where('created_at', '>=', $last7Days)
                ->whereIn('status', ['confirmed', 'completed'])
                ->count();
            
            $conversionRate7Days = $totalBookings7Days > 0 
                ? round(($confirmedBookings7Days / $totalBookings7Days) * 100, 1) 
                : 0;

            // Average booking value
            $avgBookingValue7Days = Booking::where('created_at', '>=', $last7Days)
                ->whereIn('status', ['confirmed', 'completed'])
                ->avg('total_price') ?? 0;

            $avgBookingValue30Days = Booking::where('created_at', '>=', $last30Days)
                ->whereIn('status', ['confirmed', 'completed'])
                ->avg('total_price') ?? 0;

            // Payment verification time (average)
            $avgVerificationTime = Payment::where('status', 'verified')
                ->where('verified_at', '>=', $last30Days)
                ->whereNotNull('verified_at')
                ->get()
                ->avg(function ($payment) {
                    return $payment->created_at->diffInHours($payment->verified_at);
                }) ?? 0;

            // Top performing room
            $topRoom = Room::select('rooms.id', 'rooms.name', DB::raw('COUNT(bookings.id) as booking_count'))
                ->leftJoin('bookings', 'rooms.id', '=', 'bookings.room_id')
                ->where('bookings.created_at', '>=', $last30Days)
                ->whereIn('bookings.status', ['confirmed', 'completed'])
                ->groupBy('rooms.id', 'rooms.name')
                ->orderByDesc('booking_count')
                ->first();

            return [
                'conversion_rate_7days' => $conversionRate7Days,
                'avg_booking_value_7days' => round($avgBookingValue7Days, 0),
                'avg_booking_value_30days' => round($avgBookingValue30Days, 0),
                'avg_verification_time_hours' => round($avgVerificationTime, 1),
                'top_room_name' => $topRoom->name ?? 'N/A',
                'top_room_bookings' => $topRoom->booking_count ?? 0,
                
                // Formatted values
                'formatted_avg_7days' => 'Rp ' . number_format($avgBookingValue7Days, 0, ',', '.'),
                'formatted_avg_30days' => 'Rp ' . number_format($avgBookingValue30Days, 0, ',', '.'),
            ];
        });
    }

    /**
     * Get occupied rooms count untuk hari ini
     */
    private function getOccupiedRoomsCount()
    {
        return Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereDate('check_in', '<=', today())
            ->whereDate('check_out', '>=', today())
            ->sum('rooms_count');
    }


    /**
     * Get chart data via AJAX - HANYA 7 HARI DAN 30 HARI
     */
    public function getChartData(Request $request)
    {
        $period = (int) $request->get('period', 30);
        
        // Validasi: hanya izinkan 7 atau 30 hari
        if (!in_array($period, [7, 30])) {
            $period = 30;
        }

        $data = $this->getChartDataByPeriod($period);

        return response()->json([
            'success' => true,
            'period' => $period,
            'data' => $data,
            'summary' => [
                'total_revenue' => array_sum($data['values']),
                'avg_daily_revenue' => count($data['values']) > 0 ? array_sum($data['values']) / count($data['values']) : 0,
                'peak_day' => $this->getPeakDay($data),
                'growth_trend' => $this->calculateGrowthTrend($data['values'])
            ]
        ]);
    }

    /**
     * Get stats via AJAX untuk real-time updates
     */
    public function getStats()
    {
        // Clear cache untuk mendapatkan data terbaru
        Cache::forget('dashboard_stats');
        
        $stats = $this->getDashboardStats();
        
        return response()->json([
            'success' => true,
            'timestamp' => now()->toISOString(),
            'stats' => $stats,
            'alerts' => $this->getSystemAlerts()
        ]);
    }

    /**
     * Generate chart data berdasarkan periode (HANYA 7 DAN 30 HARI)
     */
    private function getChartDataByPeriod($days)
    {
        $labels = [];
        $values = [];
        $bookingCounts = [];

        if ($days === 7) {
            // Format: Hari (Sen, Sel, Rab, Kam, Jum, Sab, Min)
            $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $dayNames[$date->dayOfWeek];
                $revenue = $this->getRevenueByDate($date);
                $bookings = $this->getBookingCountByDate($date);
                
                $values[] = $revenue;
                $bookingCounts[] = $bookings;
            }
        } else { // 30 hari
            // Format: Tanggal (1, 2, 3, ... 30)
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('d');
                $revenue = $this->getRevenueByDate($date);
                $bookings = $this->getBookingCountByDate($date);
                
                $values[] = $revenue;
                $bookingCounts[] = $bookings;
            }
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'booking_counts' => $bookingCounts,
            'period' => $days,
            'period_label' => $days === 7 ? '7 Hari Terakhir' : '30 Hari Terakhir'
        ];
    }

    /**
     * Get revenue untuk tanggal tertentu (dari payment verified)
     */
    private function getRevenueByDate($date)
    {
        return Payment::where('status', 'verified')
            ->whereDate('verified_at', $date)
            ->sum('amount');
    }

    /**
     * Get booking count untuk tanggal tertentu
     */
    private function getBookingCountByDate($date)
    {
        return Booking::whereIn('status', ['confirmed', 'completed'])
            ->whereDate('created_at', $date)
            ->count();
    }

    /**
     * Get peak day dari data chart
     */
    private function getPeakDay($data)
    {
        if (empty($data['values'])) {
            return null;
        }

        $maxValue = max($data['values']);
        $maxIndex = array_search($maxValue, $data['values']);
        
        return [
            'day' => $data['labels'][$maxIndex] ?? null,
            'revenue' => $maxValue,
            'formatted_revenue' => 'Rp ' . number_format($maxValue, 0, ',', '.')
        ];
    }

    /**
     * Calculate growth trend dari array values
     */
    private function calculateGrowthTrend($values)
    {
        if (count($values) < 2) {
            return 0;
        }

        $firstHalf = array_slice($values, 0, floor(count($values) / 2));
        $secondHalf = array_slice($values, floor(count($values) / 2));

        $firstAvg = array_sum($firstHalf) / count($firstHalf);
        $secondAvg = array_sum($secondHalf) / count($secondHalf);

        if ($firstAvg == 0) {
            return $secondAvg > 0 ? 100 : 0;
        }

        return round((($secondAvg - $firstAvg) / $firstAvg) * 100, 1);
    }

    /**
     * Get system alerts untuk dashboard
     */
    private function getSystemAlerts()
    {
        $alerts = [];

        // Alert untuk payment pending terlalu lama
        $oldPendingPayments = Payment::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subHours(24))
            ->count();

        if ($oldPendingPayments > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "Ada {$oldPendingPayments} pembayaran pending lebih dari 24 jam",
                'action_url' => route('admin.payments.index', ['status' => 'pending'])
            ];
        }

        // Alert untuk booking menunggu verifikasi
        $waitingVerification = Booking::where('status', 'waiting_verification')->count();
        if ($waitingVerification > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "Ada {$waitingVerification} booking menunggu verifikasi",
                'action_url' => route('admin.bookings.index', ['status' => 'waiting_verification'])
            ];
        }

        // Alert untuk occupancy rate tinggi
        $occupancyRate = $this->getDashboardStats()['occupancyRate'];
        if ($occupancyRate > 90) {
            $alerts[] = [
                'type' => 'success',
                'message' => "Occupancy rate tinggi: {$occupancyRate}%",
                'action_url' => route('admin.rooms.index')
            ];
        }

        return $alerts;
    }

    /**
     * Helper methods untuk status colors dan labels
     */
    private function getRoomStatusColor($status, $occupancyRate)
    {
        if ($status === 'maintenance') return 'gray';
        if ($occupancyRate >= 90) return 'red';
        if ($occupancyRate >= 70) return 'yellow';
        return 'green';
    }

    private function getBookingStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu',
            'waiting_verification' => 'Menunggu Verifikasi',
            'confirmed' => 'Terkonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    private function getBookingStatusColor($status)
    {
        $colors = [
            'pending' => 'yellow',
            'waiting_verification' => 'blue',
            'confirmed' => 'green',
            'completed' => 'green',
            'cancelled' => 'red'
        ];

        return $colors[$status] ?? 'gray';
    }

    private function getPaymentStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'failed' => 'Ditolak'
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    private function getPaymentStatusColor($status)
    {
        $colors = [
            'pending' => 'yellow',
            'verified' => 'green',
            'failed' => 'red'
        ];

        return $colors[$status] ?? 'gray';
    }

    private function getPaymentMethodLabel($method)
    {
        $labels = [
            'bank_transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'credit_card' => 'Kartu Kredit',
            'cash' => 'Tunai'
        ];

        return $labels[$method] ?? 'Transfer Bank';
    }

    /**
     * Legacy methods untuk backward compatibility
     */
    public function chartData(Request $request)
{
    return response()->json([
        'data' => [
            'labels' => [],
            'values' => []
        ]
    ]);
}


    /**
     * Calculate overall occupancy rate (legacy method)
     */
    private function calculateOccupancy()
    {
        $totalRooms = Room::sum('stock');
        $bookedRooms = $this->getOccupiedRoomsCount();

        return $totalRooms > 0 ? round(($bookedRooms / $totalRooms) * 100, 2) : 0;
    }
}