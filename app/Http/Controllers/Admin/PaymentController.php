<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display list of payments with stats
     */
    public function index(Request $request)
    {
        // Load payment HANYA yang belum verified (pending & failed)
        $query = Payment::with(['booking' => function ($q) {
            $q->select('id', 'booking_code', 'room_id', 'guest_name', 'guest_email', 'check_in', 'check_out', 'guests', 'total_price');
        }, 'booking.room:id,name'])
            ->whereIn('status', ['pending', 'failed']); // Filter hanya pending & failed

        // Search by booking code or guest name
        if ($request->filled('search')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('booking_code', 'like', '%' . $request->search . '%')
                    ->orWhere('guest_name', 'like', '%' . $request->search . '%')
                    ->orWhere('guest_email', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        $payments = $query->latest()->paginate(15);

        // Calculate stats
        $stats = [
            'pending' => Payment::where('status', 'pending')->count(),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
            'verified' => Payment::where('status', 'verified')->count(),
            'verified_amount' => Payment::where('status', 'verified')->sum('amount'),
            'total_revenue' => Payment::where('status', 'verified')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats'));
    }

    /**
     * Display single payment details
     */
    public function show(Payment $payment)
    {
        $payment->load(['booking.room']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Get payment details with full booking info (for AJAX)
     */
    public function getDetails(Payment $payment)
    {
        $payment->load(['booking.room']);

        return response()->json($payment);
    }

    /**
     * Verify payment (approve)
     */
    public function verify(Request $request, Payment $payment)
    {
        $payment->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => Auth::id(),
        ]);

        $payment->booking->update([
            'status' => 'confirmed'
        ]);

        return redirect()->back()
            ->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Reject payment
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'reason' => 'required|string'
        ]);

        $payment->update([
            'status' => 'failed',
            'rejection_reason' => $request->reason,
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
        ]);

        $payment->booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()->back()
            ->with('success', 'Pembayaran ditolak dan booking dibatalkan.');
    }

    /**
     * Delete payment record
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Data pembayaran berhasil dihapus!');
    }
}
