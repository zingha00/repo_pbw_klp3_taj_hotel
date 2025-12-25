<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access');
        }

        // Hitung statistik
        $totalRevenue = Reservation::where('status', 'paid')->sum('total_price');
        $totalReservations = Reservation::count();
        $totalCustomers = User::where('role', 'customer')->count();

        // Room statistics
        $totalRooms = Room::count();
        $availableRooms = Room::where('available', true)->count();
        $occupiedRooms = Reservation::where('status', 'paid')
            ->where('check_out', '>=', now())
            ->distinct('room_id')
            ->count('room_id');
        $maintenanceRooms = Room::where('available', false)->count();

        // Ambil semua reservasi dengan relasi room
        $reservations = Reservation::with('room')->latest()->get();

        // Recent reservations (last 5)
        $recentReservations = Reservation::with('room')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalReservations',
            'totalCustomers',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'reservations',
            'recentReservations'
        ));
    }
}