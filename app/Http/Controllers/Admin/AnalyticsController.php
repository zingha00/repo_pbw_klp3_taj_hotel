<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Show cancellation analytics dashboard
     */
    public function cancellations(Request $request)
    {
        $days = $request->get('days', 30);
        
        // Get analytics data
        $analytics = $this->getCancellationAnalytics($days);

        return view('admin.analytics.cancellations', compact('analytics', 'days'));
    }

    /**
     * Get cancellation data for AJAX requests
     */
    public function getCancellationData(Request $request)
    {
        $days = $request->get('days', 30);
        
        // Get analytics data
        $analytics = $this->getCancellationAnalytics($days);

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get cancellation analytics data
     */
    private function getCancellationAnalytics($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);

        // Total cancellations in period
        $totalCancellations = Booking::where('status', 'cancelled')
            ->where('cancelled_at', '>=', $startDate)
            ->count();

        // Total bookings in period for cancellation rate
        $totalBookings = Booking::where('created_at', '>=', $startDate)->count();
        $cancellationRate = $totalBookings > 0 
            ? round(($totalCancellations / $totalBookings) * 100, 2) 
            : 0;

        // Cancellations by day (for chart)
        $dailyCancellations = Booking::where('status', 'cancelled')
            ->where('cancelled_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(cancelled_at) as date'),
                DB::raw('count(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('d M'),
                    'count' => $item->count
                ];
            });

        // Top cancellation reasons
        $topReasons = Booking::where('status', 'cancelled')
            ->where('cancelled_at', '>=', $startDate)
            ->whereNotNull('cancellation_reason')
            ->select(
                'cancellation_reason',
                DB::raw('count(*) as count')
            )
            ->groupBy('cancellation_reason')
            ->orderByDesc('count')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'reason' => $item->cancellation_reason,
                    'count' => $item->count
                ];
            });

        // Cancellations by status before cancellation
        $cancellationsByPreviousStatus = Booking::where('status', 'cancelled')
            ->where('cancelled_at', '>=', $startDate)
            ->select(
                DB::raw('CASE 
                    WHEN cancellation_reason LIKE "%admin%" THEN "Admin Cancelled"
                    WHEN cancellation_reason LIKE "%ditolak%" THEN "Rejected Payment"
                    ELSE "User Cancelled"
                END as previous_status'),
                DB::raw('count(*) as count')
            )
            ->groupBy('previous_status')
            ->get();

        // Recent cancelled bookings
        $recentCancellations = Booking::with(['room'])
            ->where('status', 'cancelled')
            ->where('cancelled_at', '>=', $startDate)
            ->orderByDesc('cancelled_at')
            ->limit(10)
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'booking_code' => $booking->booking_code,
                    'guest_name' => $booking->guest_name,
                    'room_name' => $booking->room->name ?? 'N/A',
                    'total_price' => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
                    'cancelled_at' => $booking->cancelled_at ? $booking->cancelled_at->format('d M Y H:i') : '-',
                    'cancellation_reason' => $booking->cancellation_reason
                ];
            });

        // Average time between booking and cancellation
        $avgTimeToCancellation = Booking::where('status', 'cancelled')
            ->where('cancelled_at', '>=', $startDate)
            ->whereNotNull('cancelled_at')
            ->select(
                DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, cancelled_at)) as avg_hours')
            )
            ->first();

        $avgHoursToCancellation = $avgTimeToCancellation->avg_hours 
            ? round($avgTimeToCancellation->avg_hours, 1) 
            : 0;

        // Total refund amount (if you have refund tracking)
        $totalRefundAmount = Booking::where('status', 'cancelled')
            ->where('cancelled_at', '>=', $startDate)
            ->sum('total_price');

        return [
            'period' => [
                'days' => $days,
                'start_date' => $startDate->format('d M Y'),
                'end_date' => Carbon::now()->format('d M Y'),
            ],
            'summary' => [
                'total_cancellations' => $totalCancellations,
                'total_bookings' => $totalBookings,
                'cancellation_rate' => $cancellationRate,
                'avg_hours_to_cancellation' => $avgHoursToCancellation,
                'total_refund_amount' => 'Rp ' . number_format($totalRefundAmount, 0, ',', '.'),
            ],
            'charts' => [
                'daily_cancellations' => $dailyCancellations,
                'top_reasons' => $topReasons,
                'by_previous_status' => $cancellationsByPreviousStatus,
            ],
            'recent_cancellations' => $recentCancellations,
        ];
    }
}