<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
     public function index()
    {
        $rooms = Room::all();
        return view('frontend.rooms.index', compact('rooms'));
    }

    public function show(Room $room)
    {
        return view('frontend.rooms.show', compact('room'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_number' => 'required|unique:rooms,room_number',
            'name'        => 'required',
            'type'        => 'required', 
            'price'       => 'required|numeric',
            'capacity'    => 'required|integer',
            'length'      => 'nullable|numeric',
            'width'       => 'nullable|numeric',
            'description' => 'nullable|string',
            'facilities'  => 'nullable|array',
            'status'      => 'required|in:available,occupied,maintenance',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // upload image
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        if ($request->facilities) {
            $data['facilities'] = json_encode($request->facilities);
        }

        // derive available flag from status
        $data['available'] = $data['status'] === 'available';

        Room::create($data);

        return redirect()->back()->with('success', 'Kamar berhasil ditambahkan!');
    }

    public function update(Request $request, Room $room)
    {
        // Normalisasi dulu sebelum validate
        $request->merge([
            'type' => strtolower($request->input('type')),
            'status' => strtolower($request->input('status')),
        ]);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'room_number' => 'required|string|max:50',
            'type' => 'required|in:single,double,suite,couple,luxury',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:available,occupied,maintenance',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // image upload
        if ($request->hasFile('image')) {
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }
            $data['image'] = $request->file('image')->store('rooms', 'public');
        }

        // available selalu diturunkan dari status
        $data['available'] = $data['status'] === 'available';

        $room->update($data);

        return redirect()->route('admin.dashboard')
                        ->with('success', 'Kamar berhasil diperbarui!')
                        ->with('active_tab', 'rooms');
    }


    public function destroy(Room $room)
    {
        // Delete image if exists
        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return redirect()->back()->with('success', 'Room deleted successfully!');
    }

    public function edit(Room $room)
    {
        return view('frontend.rooms.edit', compact('room'));
    }

    public function showAdmin(Room $room)
    {
        return response()->json([
            'id' => $room->id,
            'room_number' => $room->room_number,
            'name' => $room->name,
            'type' => strtolower($room->type),
            'price' => $room->price,
            'capacity' => $room->capacity,
            'status' => strtolower($room->status),
            'description' => $room->description,
            'image' => $room->image,
        ]);
    }
}