<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::withCount(['bookings', 'reviews']);

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'bookings':
                $query->orderBy('bookings_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $users = $query->paginate(10);

        // ✅ STATISTIK YANG BERFUNGSI
        $stats = [
            // Total semua pengguna
            'total' => User::count(),
            
            // User baru yang bergabung bulan ini
            'new_this_month' => User::whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count(),
            
            // Total admin
            'admins' => User::where('role', 'admin')->count(),
            
            // User aktif dalam 7 hari terakhir (berdasarkan updated_at)
            'active' => User::where('updated_at', '>=', Carbon::now()->subDays(7))
                           ->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display the specified user (untuk AJAX modal)
     */
    public function show($id)
    {
        $user = User::withCount(['bookings', 'reviews'])->findOrFail($id);

        // Jika request AJAX (untuk modal), return JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '-',
                'role' => ucfirst($user->role ?? 'user'),
                'bookings_count' => $user->bookings_count ?? 0,
                'reviews_count' => $user->reviews_count ?? 0,
                'created_at' => $user->created_at->format('d M Y'),
            ]);
        }

        // Jika bukan AJAX, tampilkan halaman detail lengkap
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'confirmed_bookings' => $user->bookings()->where('status', 'confirmed')->count(),
            'total_spent' => $user->bookings()->where('status', 'confirmed')->sum('total_price'),
            'pending_bookings' => $user->bookings()->where('status', 'pending')->count(),
        ];

        $recentBookings = $user->bookings()
                              ->with(['room', 'payment'])
                              ->latest()
                              ->take(5)
                              ->get();

        return view('admin.users.show', compact('user', 'stats', 'recentBookings'));
    }

    /**
     * Show the form for editing user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin',
            'password' => 'nullable|string|min:8|confirmed'
        ]);

        // Update password only if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    /**
     * Change user role (untuk AJAX)
     */
    public function changeRole(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Prevent changing own role
    if ($user->id === Auth::id()) {
    return response()->json([
        'error' => 'Tidak dapat mengubah role sendiri'
    ], 403);
}


    $request->validate([
        'role' => 'required|in:admin,user'
    ]);

    $user->update([
        'role' => $request->role
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Role berhasil diubah'
    ]);
}


    /**
     * Remove the specified user
     */
    public function destroy($id)
{
    $user = User::findOrFail($id);

    // Prevent deleting own account
    if ($user->id === Auth::id()) {
        return back()->with('error', 'Tidak dapat menghapus akun sendiri');
    }

    // Check if user has active bookings
    $hasActiveBookings = $user->bookings()
        ->whereIn('status', ['pending', 'paid'])
        ->exists();

    if ($hasActiveBookings) {
        return back()->with('error', 'Tidak dapat menghapus user dengan booking aktif');
    }

    $user->delete();

    return redirect()->route('admin.users.index')
        ->with('success', 'User berhasil dihapus');
}


    /**
     * Toggle user status (active/inactive)
     */
    public function toggleStatus($id)
{
    $user = User::findOrFail($id);

    // Prevent disabling own account
    if ($user->id === Auth::id()) {
        return back()->with('error', 'Tidak dapat menonaktifkan akun sendiri');
    }

    $user->update([
        'is_active' => ! $user->is_active
    ]);

    $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

    return redirect()->back()
        ->with('success', "User berhasil {$status}");
}


    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $query = User::withCount('bookings');

        if ($request->has('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        $filename = 'users_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Name', 'Email', 'Phone', 'Role', 'Total Bookings', 'Registered']);

            // Data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->name,
                    $user->email,
                    $user->phone ?? '-',
                    $user->role,
                    $user->bookings_count,
                    $user->created_at->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}