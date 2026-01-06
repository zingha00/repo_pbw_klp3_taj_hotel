<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    // READ: Tampilkan semua rooms (Public)
    public function index()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    // READ: Tampilkan detail 1 room (Public)
    public function show($id)
    {
        $room = Room::findOrFail($id);
        return view('rooms.show', compact('room'));
    }

    // CREATE: Form tambah room (Admin only)
    public function create()
    {
        // Check if admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.rooms.create');
    }

    // CREATE: Simpan room baru (Admin only)
    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'capacity' => 'required|numeric',
            'type' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rooms', 'public');
            $validated['image'] = '/storage/' . $imagePath;
        }

        $validated['available'] = $request->has('available');

        Room::create($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Room created successfully!');
    }

    // UPDATE: Form edit room (Admin only)
    public function edit($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $room = Room::findOrFail($id);
        return view('admin.rooms.edit', compact('room'));
    }

    // UPDATE: Simpan perubahan (Admin only)
    public function update(Request $request, $id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'capacity' => 'required|numeric',
            'type' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $room = Room::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($room->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $room->image));
            }

            $imagePath = $request->file('image')->store('rooms', 'public');
            $validated['image'] = '/storage/' . $imagePath;
        }

        $validated['available'] = $request->has('available');

        $room->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Room updated successfully!');
    }

    // DELETE: Hapus room (Admin only)
    public function destroy($id)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403);
        }

        $room = Room::findOrFail($id);

        // Delete image
        if ($room->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $room->image));
        }

        $room->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Room deleted successfully!');
    }
}