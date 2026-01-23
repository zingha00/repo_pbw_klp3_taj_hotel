<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['room', 'payment']);

        // Filter untuk melihat booking yang dihapus
        if ($request->filled('show_deleted') && $request->show_deleted === 'only') {
            $query = Booking::onlyTrashed()->with(['room', 'payment']);
        } elseif ($request->filled('show_deleted') && $request->show_deleted === 'with') {
            $query = Booking::withTrashed()->with(['room', 'payment']);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('booking_code', 'like', '%' . $request->search . '%')
                  ->orWhere('guest_name', 'like', '%' . $request->search . '%')
                  ->orWhere('guest_email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('check_in', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('check_out', '<=', $request->date_to);
        }

        $bookings = $query->latest()->paginate(15);

        // Stats
        $stats = [
            'pending' => Booking::where('status', 'pending')->count(),
            'waiting_verification' => Booking::where('status', 'waiting_verification')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    /**
     * Display the specified booking
     */
    public function show($id)
    {
        try {
            // Check database connection first
            if (!DB::connection()->getPdo()) {
                throw new \Exception('Database connection failed');
            }
            
            $booking = Booking::with(['room', 'payment'])->findOrFail($id);

            // Debug info
            Log::info('Booking show request', [
                'id' => $booking->id,
                'status' => $booking->status,
                'cancellation_reason' => $booking->cancellation_reason,
                'cancelled_at' => $booking->cancelled_at,
                'cancelled_by' => $booking->cancelled_by,
                'payment_status' => $booking->payment ? $booking->payment->status : null
            ]);

            // Return JSON untuk AJAX request
            return response()->json([
                'booking_code' => $booking->booking_code,
                'user_name' => $booking->guest_name,
                'user_email' => $booking->guest_email,
                'user_phone' => $booking->guest_phone,
                'room' => [
                    'name' => $booking->room->name ?? 'Unknown Room',
                    'type' => $booking->room->type ?? 'Standard',
                ],
                'check_in' => $booking->check_in->format('d M Y'),
                'check_out' => $booking->check_out->format('d M Y'),
                'guests' => $booking->guests,
                'rooms_count' => $booking->rooms_count,
                'nights' => $booking->nights,
                'total_price' => $booking->formatted_total ?? 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
                'status' => $booking->status,
                'status_label' => $booking->status_label ?? ucfirst($booking->status),
                'rejection_reason' => $booking->rejection_reason,
                'cancellation_reason' => $booking->cancellation_reason,
                'cancelled_at' => $booking->cancelled_at ? $booking->cancelled_at->format('d M Y H:i') : null,
                'cancelled_by' => $booking->cancelled_by,
                'admin_notes' => $booking->admin_notes,
                'payment' => $booking->payment ? [
                    'method' => $booking->payment->payment_method,
                    'method_label' => $booking->payment->payment_method_label ?? ($booking->payment->payment_method === 'bank' ? 'Transfer Bank' : 'E-Wallet'),
                    'amount' => $booking->payment->formatted_amount ?? 'Rp ' . number_format($booking->payment->amount, 0, ',', '.'),
                    'status' => $booking->payment->status,
                    'proof' => $booking->payment->payment_proof ? asset('storage/' . $booking->payment->payment_proof) : null,
                ] : null,
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database connection error in booking show', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Database connection failed',
                'message' => 'MySQL server is not running. Please start XAMPP MySQL service.',
                'booking_code' => 'DB_ERROR_' . $id,
                'cancellation_reason' => 'Database connection error - MySQL server not running',
                'cancelled_at' => date('d M Y H:i'),
                'status' => 'error'
            ], 503);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Booking not found', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Booking not found',
                'message' => "Booking with ID {$id} not found"
            ], 404);

        } catch (\Exception $e) {
            Log::error('Booking show error', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Internal server error',
                'message' => $e->getMessage(),
                'booking_code' => 'ERROR_' . $id,
                'cancellation_reason' => 'System error occurred: ' . $e->getMessage(),
                'cancelled_at' => date('d M Y H:i'),
                'status' => 'error'
            ], 500);
        }
    }

    /**
     * Confirm booking payment
     */
    public function confirm($id)
    {
        try {
            $booking = Booking::with('payment')->findOrFail($id);
            
            // Cek apakah booking bisa dikonfirmasi
            if (!$booking->canBeConfirmed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak dapat dikonfirmasi. Status saat ini: ' . $booking->status_label
                ], 400);
            }

            // Cek apakah ada payment
            if (!$booking->payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking belum memiliki bukti pembayaran!'
                ], 400);
            }

            // Gunakan method dari model
            $booking->confirmBooking();

            Log::info('Admin confirmed booking', [
                'booking_id' => $booking->id,
                'admin_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dikonfirmasi!'
            ]);

        } catch (\Exception $e) {
            Log::error('Confirm booking failed', [
                'booking_id' => $id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengonfirmasi booking.'
            ], 500);
        }
    }

    /**
     * Reject booking payment
     */
    public function reject(Request $request, $id)
    {
        try {
            $booking = Booking::with('payment')->findOrFail($id);
            
            // Cek apakah booking bisa ditolak
            if (!$booking->canBeRejected()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak dapat ditolak. Status saat ini: ' . $booking->status_label
                ], 400);
            }
            
            $validated = $request->validate([
                'rejection_reason' => 'required|string|max:500'
            ], [
                'rejection_reason.required' => 'Alasan penolakan harus diisi',
                'rejection_reason.max' => 'Alasan penolakan maksimal 500 karakter'
            ]);

            // Gunakan method dari model
            $booking->rejectBooking($validated['rejection_reason']);

            Log::info('Admin rejected booking', [
                'booking_id' => $booking->id,
                'admin_id' => Auth::id(),
                'reason' => $validated['rejection_reason']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil ditolak!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Reject booking failed', [
                'booking_id' => $id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menolak booking.'
            ], 500);
        }
    }

    /**
     * Cancel a confirmed booking
     */
    public function cancel(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            $validated = $request->validate([
                'cancellation_reason' => 'required|string|min:10|max:500'
            ], [
                'cancellation_reason.required' => 'Alasan pembatalan harus diisi',
                'cancellation_reason.min' => 'Alasan pembatalan minimal 10 karakter',
                'cancellation_reason.max' => 'Alasan pembatalan maksimal 500 karakter'
            ]);

            // Check if booking can be cancelled
            if (!in_array($booking->status, ['confirmed', 'waiting_verification'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak dapat dibatalkan dengan status saat ini: ' . $booking->status
                ], 400);
            }

            // Update booking status
            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $validated['cancellation_reason'],
                'cancelled_at' => now(),
                'cancelled_by' => Auth::id(),
            ]);

            // Update payment status if exists
            if ($booking->payment) {
                $booking->payment->update([
                    'status' => 'cancelled',
                    'rejection_reason' => 'Booking dibatalkan oleh admin: ' . $validated['cancellation_reason'],
                ]);
            }

            Log::info('Admin cancelled booking', [
                'booking_id' => $booking->id,
                'booking_code' => $booking->booking_code,
                'admin_id' => Auth::id(),
                'reason' => $validated['cancellation_reason']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan.'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Booking not found for cancellation', [
                'booking_id' => $id,
                'admin_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.'
            ], 404);
            
        } catch (\Exception $e) {
            Log::error('Admin booking cancellation failed', [
                'booking_id' => $id,
                'admin_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Soft delete booking
     */
    public function destroy(Request $request, $id)
{
    try {
        $booking = Booking::with('payment')->findOrFail($id);

        // Cek apakah booking memiliki payment yang sudah verified
        if ($booking->payment && $booking->payment->status === 'verified') {
            $message = 'Tidak dapat menghapus booking yang pembayarannya sudah terverifikasi!';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }

            return redirect()->back()->with('error', $message);
        }

        DB::transaction(function () use ($booking) {
            // Hapus payment terkait jika ada
            if ($booking->payment) {
                $booking->payment->delete();
            }

            // Soft delete booking
            $booking->delete();
        });

        Log::info('Admin soft deleted booking', [
            'booking_id' => $booking->id,
            'admin_id' => Auth::id()
        ]);

        $message = 'Booking berhasil dihapus!';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        $message = 'Booking tidak ditemukan.';

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], 404);
        }

        return redirect()->back()->with('error', $message);

    } catch (\Throwable $e) {
        Log::error('Soft delete booking failed', [
            'booking_id' => $id,
            'error' => $e->getMessage()
        ]);

        $message = 'Terjadi kesalahan saat menghapus booking.';

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => $message], 500);
        }

        return redirect()->back()->with('error', $message);
    }
}


    /**
     * Force delete booking (permanent delete)
     */
    public function forceDestroy($id)
    {
        try {
            $booking = Booking::withTrashed()->findOrFail($id);
            
            // Hapus payment terkait secara permanen
            if ($booking->payment) {
                $booking->payment->forceDelete();
            }
            
            // Hapus booking secara permanen
            $booking->forceDelete();

            Log::info('Admin force deleted booking', [
                'booking_id' => $id,
                'booking_code' => $booking->booking_code,
                'admin_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dihapus secara permanen!'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Booking not found for force delete', [
                'booking_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Booking tidak ditemukan.'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Force delete booking failed', [
                'booking_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus booking: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore cancelled booking to confirmed status
     */
    public function restore($id)
{
    try {
        $booking = Booking::withTrashed()->with('payment')->findOrFail($id);

        // Kalau ini soft-deleted, pulihkan dulu recordnya
        if ($booking->trashed()) {
            $booking->restore();
        }

        // Kalau bukan cancelled, tetap bisa kamu atur rule-nya
        // if ($booking->status !== 'cancelled') {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Hanya booking yang dibatalkan yang dapat di-restore. Status saat ini: ' . $booking->status
        //     ], 400);
        // }

        $newStatus = ($booking->status === 'cancelled') ? 'confirmed' : 'waiting_verification';

        $booking->update([
        'status' => $newStatus,
        'cancellation_reason' => null,
        'cancelled_at' => null,
        'cancelled_by' => null,
        'rejection_reason' => null, // tambah ini
    ]);

        if ($booking->payment) {
    $paymentStatus = ($newStatus === 'confirmed') ? 'verified' : 'pending';
    $booking->payment->update([
        'status' => $paymentStatus,
        'rejection_reason' => null,
    ]);
}

        $message = ($newStatus === 'confirmed') 
    ? 'Booking berhasil di-restore ke status Terkonfirmasi!' 
    : 'Booking berhasil di-restore ke status Menunggu Verifikasi!';

return response()->json([
    'success' => true,
    'message' => $message
]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan.'], 404);
    } catch (\Throwable $e) {
        return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat me-restore booking.'], 500);
    }
}

}