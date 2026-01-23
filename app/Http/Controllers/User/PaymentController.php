<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Show payment page
     */
    public function index($booking_id)
    {
        $booking = Booking::with(['room', 'payment'])
            ->where('id', $booking_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (in_array($booking->status, ['confirmed', 'completed'])) {
            return redirect()->route('reservations.index')
                ->with('info', 'Booking ini sudah selesai diproses.');
        }

        if ($booking->status === 'cancelled') {
            return redirect()->route('reservations.index')
                ->with('error', 'Booking ini sudah dibatalkan.');
        }

        return view('user.payment.index', [
            'booking' => $booking,
            'payment' => $booking->payment
        ]);
    }

    /**
     * Process payment (upload proof)
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'booking_id'      => 'required|exists:bookings,id',
            'payment_method'  => 'required|in:bank,ewallet',
            'payment_proof'   => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $booking = Booking::where('id', $validated['booking_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->status !== 'pending') {
            return redirect()->route('reservations.index')
                ->with('error', 'Booking ini tidak dapat diproses.');
        }

        // Upload bukti pembayaran
        $proofPath = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        // Create / Update payment
        Payment::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'amount'         => $booking->total_price,
                'payment_method' => $validated['payment_method'],
                'status'         => 'pending',
                'payment_proof'  => $proofPath,
                'paid_at'        => now(),
            ]
        );

        // Update booking status
        $booking->update([
            'status' => 'waiting_verification'
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin (maks. 1x24 jam).');
    }

    /**
     * Re-upload payment proof (if rejected)
     */
    public function uploadProof(Request $request, $payment_id)
    {
        $payment = Payment::with('booking')
            ->where('id', $payment_id)
            ->firstOrFail();

        if ($payment->booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Delete old proof
        if ($payment->payment_proof && Storage::disk('public')->exists($payment->payment_proof)) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        // Upload new proof
        $proofPath = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        $payment->update([
            'payment_proof'  => $proofPath,
            'payment_status' => 'pending',
            'paid_at'        => now(),
        ]);

        $payment->booking->update([
            'status' => 'waiting_verification'
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Bukti pembayaran berhasil diupload ulang. Menunggu verifikasi admin.');
    }
}
