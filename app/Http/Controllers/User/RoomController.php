<?php

namespace App\Http\Controllers\User;

use App\Models\RoomFacility;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms
     */
    public function index(Request $request)
    {
        // ✅ SIMPLE & KONSISTEN dengan HomeController
        $query = Room::where('status', 'available')
            ->with('facilities') // ← SAMA dengan HomeController (tanpa roomImages)
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Price filter
        if ($request->filled('price')) {
            switch ($request->price) {
                case 'low':
                    $query->where('price', '<', 1000000);
                    break;
                case 'mid':
                    $query->whereBetween('price', [1000000, 2000000]);
                    break;
                case 'high':
                    $query->where('price', '>', 2000000);
                    break;
            }
        }

        // Capacity filter
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Facility filter (many-to-many relationship)
        if ($request->filled('facility')) {
            $query->whereHas('facilities', function ($q) use ($request) {
                $q->where('room_facilities.id', $request->facility);
            });
        }

        // Sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price-asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'popular':
                    $query->orderBy('views', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            // Default sorting: rating tertinggi, lalu terbaru
            $query->orderBy('reviews_avg_rating', 'desc')
                  ->orderBy('created_at', 'desc');
        }

        // Paginate dengan query string
        $rooms = $query->paginate(12)->withQueryString();

        // Get all facilities untuk filter
        $facilities = RoomFacility::orderBy('name')->get();

        return view('user.rooms.index', compact('rooms', 'facilities'));
    }

    /**
     * Display the specified room
     */
    public function show($id)
    {
        // Get room by ID or slug
        $room = Room::where('id', $id)
            ->orWhere('slug', $id)
            ->with(['roomImages', 'facilities', 'reviews' => function ($query) {
                $query->with('user')->latest()->limit(10);
            }])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->firstOrFail();

        // Increment view count
        $room->increment('views');

        // Get related rooms (same price range, different room)
        $relatedRooms = Room::where('id', '!=', $room->id)
            ->where('status', 'available')
            ->with('facilities') // ← KONSISTEN: hanya facilities
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->whereBetween('price', [
                $room->price * 0.7,
                $room->price * 1.3
            ])
            ->orderBy('reviews_avg_rating', 'desc')
            ->limit(4)
            ->get();

        return view('user.rooms.show', compact('room', 'relatedRooms'));
    }

    /**
     * Search available rooms by date and capacity
     */
    public function search(Request $request)
    {
        $request->validate([
            'checkin' => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
            'guests' => 'nullable|integer|min:1|max:10',
            'rooms_count' => 'nullable|integer|min:1|max:5',
        ]);

        $checkin = $request->input('checkin');
        $checkout = $request->input('checkout');
        $guests = $request->input('guests', 2);
        $roomsCount = $request->input('rooms_count', 1);

        // Query rooms yang tersedia
        $query = Room::where('status', 'available')
            ->where('stock', '>', 0)
            ->where('capacity', '>=', $guests)
            ->with('facilities') // ← KONSISTEN: hanya facilities
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');

        // Ambil semua room IDs
        $allRoomIds = $query->pluck('id');

        // Hitung booking yang konflik untuk setiap room
        $bookedRooms = Booking::whereIn('room_id', $allRoomIds)
            ->whereIn('status', ['pending', 'waiting_verification', 'confirmed'])
            ->where(function ($q) use ($checkin, $checkout) {
                $q->whereBetween('check_in', [$checkin, $checkout])
                    ->orWhereBetween('check_out', [$checkin, $checkout])
                    ->orWhere(function ($q2) use ($checkin, $checkout) {
                        $q2->where('check_in', '<=', $checkin)
                            ->where('check_out', '>=', $checkout);
                    });
            })
            ->selectRaw('room_id, SUM(rooms_count) as total_booked')
            ->groupBy('room_id')
            ->pluck('total_booked', 'room_id');

        // Filter rooms yang masih available
        $availableRoomIds = [];
        foreach ($query->get() as $room) {
            $bookedCount = $bookedRooms[$room->id] ?? 0;
            $availableCount = $room->stock - $bookedCount;
            
            if ($availableCount >= $roomsCount) {
                $availableRoomIds[] = $room->id;
            }
        }

        // Apply filter ke query
        $query->whereIn('id', $availableRoomIds);

        $rooms = $query->orderBy('reviews_avg_rating', 'desc')
                       ->paginate(12)
                       ->withQueryString();

        // Get all facilities untuk filter
        $facilities = RoomFacility::orderBy('name')->get();

        return view('user.rooms.index', compact('rooms', 'facilities', 'checkin', 'checkout', 'guests', 'roomsCount'));
    }

    /**
     * Check room availability
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($request->room_id);

        // Hitung jumlah kamar yang sudah dibooking
        $bookedRooms = Booking::where('room_id', $request->room_id)
            ->whereIn('status', ['pending', 'waiting_verification', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in', [$request->check_in, $request->check_out])
                    ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('check_in', '<=', $request->check_in)
                            ->where('check_out', '>=', $request->check_out);
                    });
            })
            ->sum('rooms_count');

        $available = $room->stock - $bookedRooms;

        return response()->json([
            'available' => $available > 0,
            'rooms_available' => max(0, $available),
            'total_stock' => $room->stock,
            'price_per_night' => $room->price,
            'message' => $available > 0
                ? "Tersedia {$available} kamar"
                : 'Kamar tidak tersedia pada tanggal tersebut'
        ]);
    }
}