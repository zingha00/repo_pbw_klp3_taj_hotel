<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Payment;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display admin profile
     */
    public function index()
{
    // Get admin statistics
    $stats = [
        'totalBookingsManaged' => Booking::count(),
        'pendingBookings' => Booking::where('status', 'pending')->count(),
        'totalRevenueManaged' => Payment::where('status', 'verified')->sum('amount'),
        'reviewsManaged' => Review::count(),
    ];

    return view('admin.profile.index', compact('stats'));
}

    /**
     * Update admin profile information
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        // ✅ SOLUSI: Ambil user dari database langsung
        $admin = User::findOrFail(Auth::id());
        
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->phone = $validated['phone'] ?? null;
        $admin->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Change admin password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        // ✅ SOLUSI: Ambil user dari database langsung
        $admin = User::findOrFail(Auth::id());
        $admin->password = Hash::make($request->password);
        $admin->save();

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Update admin avatar
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // ✅ SOLUSI: Ambil user dari database langsung
        $admin = User::findOrFail(Auth::id());

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($admin->avatar && file_exists(public_path('storage/' . $admin->avatar))) {
                unlink(public_path('storage/' . $admin->avatar));
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Update avatar
            $admin->avatar = $path;
            $admin->save();

            // Return JSON response untuk AJAX
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui!',
                    'avatar_url' => asset('storage/' . $path)
                ]);
            }

            return back()->with('success', 'Foto profil berhasil diperbarui!');
        }

        // Handle jika gagal upload
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload foto profil.'
            ], 400);
        }

        return back()->with('error', 'Gagal mengupload foto profil.');
    }
}