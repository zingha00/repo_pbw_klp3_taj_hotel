<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $rooms = Room::all();

        $reservations = Reservation::with(['user','room'])
            ->latest()
            ->get();

        $recentReservations = Reservation::with(['user','room'])
            ->latest()
            ->take(5)
            ->get();

        $totalRevenue = Reservation::where('status','confirmed')->sum('total_price');
        $totalReservations = Reservation::count();
        $totalCustomers = User::where('role','!=','admin')->count();
        $availableRooms = Room::where('status','available')->count();

        return view('admin.dashboard', compact(
            'rooms',
            'reservations',
            'recentReservations',
            'totalReservations',
            'totalCustomers',
            'availableRooms',
            'totalRevenue'
        ));
    }

    public function dashboard()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $rooms = Room::all();

        // Statistik
        $totalRevenue = Reservation::where('status','confirmed')->sum('total_price');
        $totalReservations = Reservation::count();
        $totalCustomers = User::where('role','customer')->count();

        // Statistik kamar
        $totalRooms = Room::count();
        $availableRooms = Room::where('status','available')->count();
        $maintenanceRooms = Room::where('status','maintenance')->count();

        $occupiedRooms = Reservation::where('status','confirmed')
            ->where('check_out','>=',now())
            ->distinct('room_id')
            ->count('room_id');

        // Reservasi
        $reservations = Reservation::with(['user','room'])->latest()->get();
        $recentReservations = Reservation::with(['user','room'])->latest()->take(5)->get();
        $allReservations = Reservation::with(['user','room'])->latest()->get();

        return view('admin.dashboard', compact(
            'rooms',
            'totalRevenue',
            'totalReservations',
            'totalCustomers',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'reservations',
            'recentReservations',
            'allReservations'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms',
            'name' => 'required',
            'type' => 'required|in:single,double,suite,couple,luxury',
            'price' => 'required|numeric',
            'capacity' => 'required|integer',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'description' => 'nullable',
            'status' => 'required|in:available,occupied,maintenance',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facilities' => 'nullable|array'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rooms','public');
        }

        Room::create($validated);

        return back()->with('success','Kamar berhasil ditambahkan');
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms,room_number,'.$room->id,
            'name' => 'required',
            'type' => 'required|in:single,double,suite,couple,luxury',
            'price' => 'required|numeric',
            'capacity' => 'required|integer',
            'length' => 'nullable|numeric',
            'width' => 'nullable|numeric',
            'description' => 'nullable',
            'status' => 'required|in:available,occupied,maintenance',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facilities' => 'nullable|array'
        ]);

        if ($request->hasFile('image')) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $validated['image'] = $request->file('image')->store('rooms','public');
        }

        $room->update($validated);

        return back()->with('success','Room updated successfully!');
    }

    public function confirmReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->update(['status'=>'confirmed']);

        $reservation->room->update([
            'status' => 'occupied'
        ]);

        return back()->with('success','Reservation confirmed!');
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->update(['status'=>'cancelled']);

        $reservation->room->update([
            'status' => 'available'
        ]);

        return back()->with('success','Reservation cancelled!');
    }
}
