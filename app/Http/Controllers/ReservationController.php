<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // READ: Tampilkan reservasi user
    public function myReservations()
    {
        $reservations = Reservation::with('room')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

        return view('reservations.my-reservations', compact('reservations'));
    }

    // CREATE: Form booking
    public function create($roomId)
    {
        $room = Room::findOrFail($roomId);
        return view('reservations.create', compact('room'));
    }

    // CREATE: Simpan reservasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required',
            'phone' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        
        // Hitung total hari
        $checkIn = Carbon::parse($validated['check_in']);
        $checkOut = Carbon::parse($validated['check_out']);
        $days = $checkOut->diffInDays($checkIn);
        
        // Hitung total harga
        $totalPrice = $days * $room->price;

        Reservation::create([
            'user_id' => auth()->id(),
            'room_id' => $validated['room_id'],
            'guest_name' => $validated['guest_name'],
            'phone' => $validated['phone'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        return redirect()->route('reservations.my')->with('success', 'Reservasi berhasil dibuat!');
    }

    // DELETE: Batalkan reservasi
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        return redirect()->route('reservations.my')->with('success', 'Reservasi berhasil dibatalkan!');
    }
}