<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\RoomFacility;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with(['roomImages', 'facilities'])->withCount('bookings');


        // SEARCH
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // SORT
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        $rooms = $query->paginate(10);

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        // ✅ Ambil semua fasilitas untuk checkbox
        $facilities = RoomFacility::all();

        return view('admin.rooms.create', compact('facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'size' => 'required|string',
            'stock' => 'required|integer|min:0',
            'facilities' => 'required|array|min:1', // ✅ Array of facility IDs
            'facilities.*' => 'exists:room_facilities,id',
            'status' => 'required|in:available,unavailable,maintenance',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // ✅ MAIN IMAGE (required)
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // ✅ GALLERY IMAGES (optional)
        ], [
            'image.required' => 'Gambar utama kamar wajib diupload',
            'image.image' => 'File harus berupa gambar',
            'image.max' => 'Ukuran gambar maksimal 2MB',
            'facilities.required' => 'Pilih minimal 1 fasilitas',
            'facilities.*.exists' => 'Fasilitas tidak valid',
        ]);

        DB::beginTransaction();

        try {
            // ✅ UPLOAD MAIN IMAGE
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('rooms', 'public');
            }

            // ✅ AUTO GENERATE SLUG
            $validated['slug'] = Str::slug($validated['name']);

            // ✅ Cek duplicate slug
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (
                Room::withTrashed()
                ->where('slug', $validated['slug'])
                ->exists()
            ) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // ✅ SET DEFAULT VALUES
            $validated['rating'] = 0;
            $validated['reviews_count'] = 0;
            $validated['views'] = 0;

            // ✅ HAPUS facilities dari validated (tidak disimpan di tabel rooms)
            $facilityIds = $validated['facilities'];
            unset($validated['facilities']);

            // ✅ CREATE ROOM
            $room = Room::create($validated);

            // ✅ ATTACH FACILITIES (many-to-many)
            $room->facilities()->attach($facilityIds);

            // ✅ UPLOAD GALLERY IMAGES ke tabel room_images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('rooms', 'public');

                    RoomImage::create([
                        'room_id' => $room->id,
                        'image_path' => $imagePath,
                        'order' => $index + 1,
                        'is_primary' => false,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Kamar berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus uploaded files jika terjadi error
            if (isset($validated['image'])) {
                Storage::disk('public')->delete($validated['image']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Room $room)
    {
        // ✅ Load relasi
        $room->load(['roomImages', 'facilities']);


        // ✅ Ambil semua fasilitas untuk checkbox
        $facilities = RoomFacility::all();

        return view('admin.rooms.edit', compact('room', 'facilities'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'size' => 'required|string',
            'stock' => 'required|integer|min:0',
            'facilities' => 'required|array|min:1',
            'facilities.*' => 'exists:room_facilities,id',
            'status' => 'required|in:available,unavailable,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // ✅ MAIN IMAGE (optional saat update)
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // ✅ NEW GALLERY IMAGES
            'remove_images' => 'nullable|array', // ✅ IDs gambar dari tabel room_images
            'remove_images.*' => 'exists:room_images,id',
            'remove_main_image' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            // ✅ UPDATE SLUG jika nama berubah
            if ($validated['name'] !== $room->name) {
                $newSlug = Str::slug($validated['name']);
                $originalSlug = $newSlug;
                $counter = 1;

                while (Room::where('slug', $newSlug)->where('id', '!=', $room->id)->exists()) {
                    $newSlug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $validated['slug'] = $newSlug;
            }

            // ✅ HANDLE MAIN IMAGE
            if ($request->hasFile('image')) {
                // Hapus main image lama
                if ($room->image) {
                    Storage::disk('public')->delete($room->image);
                }
                $validated['image'] = $request->file('image')->store('rooms', 'public');
            } elseif ($request->boolean('remove_main_image')) {
                // Hapus main image jika diminta
                if ($room->image) {
                    Storage::disk('public')->delete($room->image);
                    $validated['image'] = null;
                }
            }

            // ✅ SYNC FACILITIES (update many-to-many)
            $facilityIds = $validated['facilities'];
            unset($validated['facilities']);

            $room->facilities()->sync($facilityIds);

            // ✅ HANDLE REMOVE GALLERY IMAGES
            if ($request->filled('remove_images')) {
                $imagesToDelete = RoomImage::whereIn('id', $request->remove_images)
                    ->where('room_id', $room->id)
                    ->get();

                foreach ($imagesToDelete as $img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }

            // ✅ HANDLE ADD NEW GALLERY IMAGES
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imagePath = $image->store('rooms', 'public');

                    RoomImage::create([
                        'room_id' => $room->id,
                        'image_path' => $imagePath,
                        'order' => $index + 1,
                        'is_primary' => false,
                    ]);
                }
            }

            // ✅ UPDATE ROOM DATA
            $room->update($validated);

            DB::commit();

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Kamar berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Room $room)
    {

        $room->load('roomImages');
        // ✅ CEK BOOKING AKTIF
        $activeBookings = $room->bookings()
            ->whereIn('status', ['pending', 'paid', 'checkin'])
            ->count();

        if ($activeBookings > 0) {
            return redirect()->route('admin.rooms.index')
                ->with('error', 'Tidak dapat menghapus kamar dengan booking aktif! Ada ' . $activeBookings . ' booking yang sedang berjalan.');
        }

        DB::beginTransaction();

        try {
            // ✅ HAPUS MAIN IMAGE
            if ($room->image) {
                Storage::disk('public')->delete($room->image);
            }

            // ✅ HAPUS SEMUA GALLERY IMAGES dari tabel room_images
            foreach ($room->roomImages as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }


            // ✅ DETACH FACILITIES (many-to-many)
            $room->facilities()->detach();

            // ✅ DELETE ROOM (soft delete)
            $room->delete();

            DB::commit();

            return redirect()->route('admin.rooms.index')
                ->with('success', 'Kamar berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('admin.rooms.index')
                ->with('error', 'Gagal menghapus kamar: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Room $room)
    {
        $newStatus = match ($room->status) {
            'available' => 'unavailable',
            'unavailable' => 'available',
            'maintenance' => 'available',
            default => 'available',
        };

        $room->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Status kamar berhasil diubah menjadi ' . $newStatus,
            'status' => $newStatus,
        ]);
    }

    public function show(Room $room)
    {
        $room->load([
            'roomImages',
            'facilities',
            'bookings' => fn($q) => $q->latest()->limit(10),
            'reviews' => fn($q) => $q->latest()->limit(5),
        ]);


        return view('admin.rooms.show', compact('room'));
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($request->room_id);

        $bookedRooms = Booking::where('room_id', $request->room_id)
            ->whereIn('status', ['pending', 'paid', 'checkin'])
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
            'count' => max(0, $available),
            'total_stock' => $room->stock,
            'booked' => $bookedRooms,
            'message' => $available > 0
                ? "Tersedia {$available} dari {$room->stock} kamar"
                : 'Kamar tidak tersedia pada tanggal tersebut',
        ]);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'room_ids' => 'required|array',
            'room_ids.*' => 'exists:rooms,id',
            'status' => 'required|in:available,unavailable,maintenance',
        ]);

        Room::whereIn('id', $request->room_ids)->update(['status' => $request->status]);

        return redirect()->route('admin.rooms.index')
            ->with('success', count($request->room_ids) . ' kamar berhasil diupdate statusnya!');
    }

    /**
     * ✅ AJAX: Delete single gallery image
     */
    public function deleteRoomImage(Request $request)
    {
        $request->validate([
            'image_id' => 'required|exists:room_images,id',
        ]);

        try {
            $image = RoomImage::findOrFail($request->image_id);

            // Hapus file dari storage
            Storage::disk('public')->delete($image->image_path);

            // Hapus dari database
            $image->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar: ' . $e->getMessage(),
            ], 500);
        }
    }
}