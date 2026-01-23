<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Show the booking form
     */
    public function create($room_id)
    {
        $room = Room::findOrFail($room_id);

        // Pre-fill dates from session if available
        $checkin = session('search_checkin', today()->format('Y-m-d'));
        $checkout = session('search_checkout', today()->addDay()->format('Y-m-d'));

        return view('user.bookings.create', compact('room', 'checkin', 'checkout'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
            'rooms_count' => 'required|integer|min:1'
        ]);

        $room = Room::findOrFail($validated['room_id']);

        // Calculate nights and total
        $checkin = \Carbon\Carbon::parse($validated['check_in']);
        $checkout = \Carbon\Carbon::parse($validated['check_out']);
        $nights = $checkin->diffInDays($checkout);
        $total = $room->price * $nights * $validated['rooms_count'];

        // 🔴 SNAPSHOT HARGA SAAT BOOKING
        $roomPrice = $room->price;

        // Hitung total
        $total = $roomPrice * $nights * $validated['rooms_count'];

        // Generate booking code
        $bookingCode = 'BK' . strtoupper(Str::random(8));

        // Create booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'room_id' => $room->id,
            'booking_code' => $bookingCode,
            'guest_name' => $validated['name'],
            'guest_email' => $validated['email'],
            'guest_phone' => $validated['phone'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guests' => $validated['guests'],
            'rooms_count' => $validated['rooms_count'],
            'nights' => $nights,
            'room_price'   => $roomPrice,
            'total_price' => $total,
            'status' => 'pending'
        ]);

        return redirect()->route('payment.index', $booking->id)
            ->with('success', 'Pemesanan berhasil dibuat!');
    }

    /**
     * Display user's reservations/bookings list
     */
    public function myReservations()
    {
        $bookings = Booking::with(['room.images']) // 👈 TAMBAHKAN .images
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.reservations.index', compact('bookings'));
    }

    /**
     * Display booking details
     */
    public function show($id)
    {
        $booking = Booking::with(['room', 'review'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('user.bookings.detail', compact('booking'));
    }

    /**
     * Cancel a booking
     */
    public function cancel(Booking $booking)
    {
        // Pastikan booking milik user
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya boleh dibatalkan pada status ini
        if (!in_array($booking->status, ['pending', 'waiting_verification'])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan');
        }

        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()
            ->route('reservations.index')
            ->with('success', 'Pesanan berhasil dibatalkan');
    }


    /**
     * Mark booking as completed
     */
    public function complete($id)
    {
        $booking = Booking::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->findOrFail($id);

        $booking->update(['status' => 'completed']);

        return redirect()->route('reservations.index')
            ->with('success', 'Pesanan ditandai selesai');
    }
}
