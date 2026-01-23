<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelProfileController extends Controller
{
    public function index()
    {
        $profile = HotelProfile::first() ?? new HotelProfile();

        return view('admin.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'facilities' => 'nullable|array',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
        ]);

        if (isset($validated['facilities'])) {
            $validated['facilities'] = json_encode($validated['facilities']);
        }

        $profile = HotelProfile::first();

        if ($profile) {
            $profile->update($validated);
        } else {
            HotelProfile::create($validated);
        }

        return redirect()->route('admin.hotel.index')
            ->with('success', 'Profil hotel berhasil diperbarui!');
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $profile = HotelProfile::first() ?? HotelProfile::create([]);

        // Delete old logo
        if ($profile->logo) {
            Storage::disk('public')->delete($profile->logo);
        }

        $path = $request->file('logo')->store('hotel/logo', 'public');
        $profile->update(['logo' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Logo berhasil diupload',
            'path' => Storage::url($path)
        ]);
    }

    public function uploadPhotos(Request $request)
    {
        $request->validate([
            'photos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $profile = HotelProfile::first() ?? HotelProfile::create([]);
        $photos = json_decode($profile->photos, true) ?? [];

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('hotel/photos', 'public');
                $photos[] = $path;
            }
        }

        $profile->update(['photos' => json_encode($photos)]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diupload'
        ]);
    }

    public function deletePhoto($index)
    {
        $profile = HotelProfile::first();

        if (!$profile) {
            return response()->json(['success' => false], 404);
        }

        $photos = json_decode($profile->photos, true) ?? [];

        if (isset($photos[$index])) {
            Storage::disk('public')->delete($photos[$index]);
            unset($photos[$index]);
            $photos = array_values($photos); // Reindex array
        }

        $profile->update(['photos' => json_encode($photos)]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus'
        ]);
    }
}