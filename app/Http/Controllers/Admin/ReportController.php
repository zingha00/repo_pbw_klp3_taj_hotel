<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use App\Models\Payment;
use App\Exports\BookingsExport;
use App\Exports\RevenueExport;
use App\Exports\RoomsExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        // Summary statistics
        $stats = [
            'total_bookings' => Booking::count(),
            'total_revenue' => Payment::where('status', 'verified')->sum('amount'),
            'total_rooms' => Room::count(),
            'total_users' => User::where('role', 'user')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'completed_bookings' => Booking::where('status', 'confirmed')->count(),
            'this_month_revenue' => Payment::where('status', 'verified')
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
            'this_month_bookings' => Booking::whereMonth('created_at', now()->month)->count(),
        ];

        // Get period from request (default: 30 days)
        $period = $request->get('period', '30');
        $chartData = $this->getChartData($period);

        // Top rooms by bookings
        $topRooms = Room::withCount([
            'bookings' => function ($query) {
                $query->where('status', 'confirmed');
            }
        ])
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        // Recent bookings
        $recentBookings = Booking::with(['room'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'stats',
            'chartData',
            'topRooms',
            'recentBookings',
            'period'
        ));
    }

    /**
     * Get chart data based on period
     */
    private function getChartData($period)
    {
        $data = [];
        $labels = [];
        
        switch ($period) {
            case '7':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $revenue = Payment::where('status', 'verified')
                        ->whereDate('created_at', $date)
                        ->sum('amount');
                    
                    $labels[] = $date->format('d M');
                    $data[] = $revenue;
                }
                break;
                
            case '30':
                // Last 30 days (grouped by day)
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $revenue = Payment::where('status', 'verified')
                        ->whereDate('created_at', $date)
                        ->sum('amount');
                    
                    $labels[] = $date->format('d/m');
                    $data[] = $revenue;
                }
                break;
                
            case '60':
                // Last 2 months (grouped by week)
                for ($i = 7; $i >= 0; $i--) {
                    $startWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                    $endWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                    
                    $revenue = Payment::where('status', 'verified')
                        ->whereBetween('created_at', [$startWeek, $endWeek])
                        ->sum('amount');
                    
                    $labels[] = $startWeek->format('d M') . ' - ' . $endWeek->format('d M');
                    $data[] = $revenue;
                }
                break;
                
            case '180':
                // Last 6 months (grouped by month)
                for ($i = 5; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $revenue = Payment::where('status', 'verified')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('amount');
                    
                    $labels[] = $date->format('M Y');
                    $data[] = $revenue;
                }
                break;
                
            case '365':
                // Last 12 months (grouped by month)
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $revenue = Payment::where('status', 'verified')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('amount');
                    
                    $labels[] = $date->format('M Y');
                    $data[] = $revenue;
                }
                break;
                
            default:
                // Default to 30 days
                return $this->getChartData('30');
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
            'period' => $period
        ];
    }

    /**
     * Generate revenue report
     */
    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Daily revenue
        $revenue = Booking::whereIn('status', ['paid', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as bookings, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = $revenue->sum('total');
        $totalBookings = $revenue->sum('bookings');
        $averageDaily = $revenue->count() > 0 ? $totalRevenue / $revenue->count() : 0;

        // Revenue by room type
        $revenueByRoom = Room::withSum([
            'bookings' => function ($query) use ($startDate, $endDate) {
                $query->whereIn('status', ['paid', 'completed'])
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }
        ], 'total_price')
            ->get()
            ->filter(function ($room) {
                return $room->bookings_sum_total_price > 0;
            });

        // Revenue by payment method
        $revenueByMethod = Booking::whereIn('status', ['paid', 'completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(total_price) as total')
            ->groupBy('payment_method')
            ->get();

        return view('admin.reports.revenue', compact(
            'revenue',
            'totalRevenue',
            'totalBookings',
            'averageDaily',
            'revenueByRoom',
            'revenueByMethod',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate booking report
     */
    public function bookings(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');

        $query = Booking::with(['room', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        // Filter by status
        if ($status != 'all') {
            $query->where('status', $status);
        }

        $bookings = $query->latest()->paginate(20);

        // Status breakdown
        $statusCounts = [
            'all' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
            'pending' => Booking::where('status', 'pending')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'paid' => Booking::where('status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'completed' => Booking::where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
            'cancelled' => Booking::where('status', 'cancelled')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count(),
        ];

        return view('admin.reports.bookings', compact(
            'bookings',
            'statusCounts',
            'startDate',
            'endDate',
            'status'
        ));
    }

    /**
     * Generate room occupancy report
     */
    public function occupancy(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::parse($month)->startOfMonth();
        $endDate = Carbon::parse($month)->endOfMonth();

        $rooms = Room::all();
        $totalDays = $startDate->diffInDays($endDate) + 1;

        $occupancyData = [];

        foreach ($rooms as $room) {
            // Calculate booked nights
            $bookedNights = Booking::where('room_id', $room->id)
                ->whereIn('status', ['paid', 'completed'])
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('check_in', [$startDate, $endDate])
                        ->orWhereBetween('check_out', [$startDate, $endDate])
                        ->orWhere(function ($q2) use ($startDate, $endDate) {
                            $q2->where('check_in', '<=', $startDate)
                                ->where('check_out', '>=', $endDate);
                        });
                })
                ->get()
                ->sum(function ($booking) use ($startDate, $endDate) {
                    $checkIn = Carbon::parse($booking->check_in);
                    $checkOut = Carbon::parse($booking->check_out);

                    $effectiveCheckIn = $checkIn->lt($startDate) ? $startDate : $checkIn;
                    $effectiveCheckOut = $checkOut->gt($endDate) ? $endDate : $checkOut;

                    return $effectiveCheckIn->diffInDays($effectiveCheckOut);
                });

            $occupancyRate = $totalDays > 0 ? round(($bookedNights / $totalDays) * 100, 2) : 0;

            $occupancyData[] = [
                'room' => $room,
                'booked_nights' => $bookedNights,
                'total_nights' => $totalDays,
                'occupancy_rate' => $occupancyRate,
                'revenue' => $room->bookings()
                    ->whereIn('status', ['paid', 'completed'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('total_price')
            ];
        }

        // Sort by occupancy rate
        $occupancyData = collect($occupancyData)->sortByDesc('occupancy_rate')->values();

        // Overall occupancy
        $totalPossibleNights = $rooms->count() * $totalDays;
        $totalBookedNights = collect($occupancyData)->sum('booked_nights');
        $overallOccupancy = $totalPossibleNights > 0
            ? round(($totalBookedNights / $totalPossibleNights) * 100, 2)
            : 0;

        return view('admin.reports.occupancy', compact(
            'occupancyData',
            'overallOccupancy',
            'month',
            'totalDays'
        ));
    }

    /**
     * Export report to PDF
     */
    public function exportPDF(Request $request)
    {
        $type = $request->input('type', 'bookings'); // bookings, revenue, rooms
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');

        $data = $this->getReportData($type, $startDate, $endDate, $status);
        
        $pdf = Pdf::loadView('admin.reports.pdf.' . $type, compact('data', 'startDate', 'endDate', 'type'))
                  ->setPaper('a4', 'landscape');

        $filename = $type . '_report_' . Carbon::parse($startDate)->format('Y-m-d') . '_to_' . Carbon::parse($endDate)->format('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Export report to Excel
     */
    public function exportExcel(Request $request)
    {
        $type = $request->input('type', 'bookings'); // bookings, revenue, rooms
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $status = $request->input('status', 'all');

        $filename = $type . '_report_' . Carbon::parse($startDate)->format('Y-m-d') . '_to_' . Carbon::parse($endDate)->format('Y-m-d') . '.xlsx';

        switch ($type) {
            case 'revenue':
                return Excel::download(new RevenueExport($startDate, $endDate), $filename);
            case 'rooms':
                return Excel::download(new RoomsExport($startDate, $endDate), $filename);
            case 'bookings':
            default:
                return Excel::download(new BookingsExport($startDate, $endDate, $status), $filename);
        }
    }

    /**
     * Get report data for PDF generation
     */
    private function getReportData($type, $startDate, $endDate, $status = null)
    {
        switch ($type) {
            case 'revenue':
                return [
                    'payments' => Payment::with(['booking.room'])
                        ->where('status', 'verified')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->orderBy('created_at', 'desc')
                        ->get(),
                    'total_revenue' => Payment::where('status', 'verified')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->sum('amount'),
                    'total_transactions' => Payment::where('status', 'verified')
                        ->whereBetween('created_at', [$startDate, $endDate])
                        ->count(),
                ];

            case 'rooms':
                return [
                    'rooms' => Room::withCount([
                        'bookings' => function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('created_at', [$startDate, $endDate])
                                  ->where('status', 'confirmed');
                        }
                    ])
                    ->withSum([
                        'bookings' => function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('created_at', [$startDate, $endDate])
                                  ->where('status', 'confirmed');
                        }
                    ], 'total_price')
                    ->orderBy('bookings_count', 'desc')
                    ->get(),
                ];

            case 'bookings':
            default:
                $query = Booking::with(['room'])
                    ->whereBetween('created_at', [$startDate, $endDate]);

                if ($status && $status !== 'all') {
                    $query->where('status', $status);
                }

                return [
                    'bookings' => $query->orderBy('created_at', 'desc')->get(),
                    'total_bookings' => $query->count(),
                    'total_revenue' => $query->where('status', 'confirmed')->sum('total_price'),
                ];
        }
    }

    /**
     * Customer report
     */
    public function customers(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $customers = User::where('role', 'user')
            ->withCount([
                    'bookings' => function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('created_at', [$startDate, $endDate]);
                    }
                ])
            ->withSum([
                    'bookings' => function ($query) use ($startDate, $endDate) {
                        $query->whereIn('status', ['paid', 'completed'])
                            ->whereBetween('created_at', [$startDate, $endDate]);
                    }
                ], 'total_price')
            ->orderBy('bookings_sum_total_price', 'desc')
            ->paginate(20);

        return view('admin.reports.customers', compact('customers', 'startDate', 'endDate'));
    }
}