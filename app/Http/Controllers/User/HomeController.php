<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Review;
use App\Models\HotelProfile;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display homepage
     */
    public function index()
    {
        // Get hotel profile
        $hotelProfile = HotelProfile::first();

        // ✅ SIMPLE LOGIC: Ambil semua kamar available, urutkan terbaru, max 6
        $popularRooms = Room::where('status', 'available')
            ->with('facilities') // Load relasi facilities
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest() // Urutkan terbaru
            ->take(6)
            ->get();

        // Get recent reviews (approved, high rated)
        $reviews = Review::approved()
            ->where('rating', '>=', 4)
            ->with(['user', 'room'])
            ->latest()
            ->take(6)
            ->get();

        // Calculate average rating
        $averageRating = Review::approved()->avg('rating') ?? 0;

        // Get room types for search
        $roomTypes = [
            'standard' => 'Standard',
            'deluxe' => 'Deluxe',
            'suite' => 'Suite',
            'presidential' => 'Presidential',
        ];

        return view('user.home.index', compact(
            'hotelProfile',
            'popularRooms',
            'reviews',
            'averageRating',
            'roomTypes'
        ));
    }
}