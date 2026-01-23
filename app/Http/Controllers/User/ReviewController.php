<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'booking_id' => ['required', 'exists:bookings,id'],
        'rating' => ['required', 'integer', 'min:1', 'max:5'],
        'cleanliness_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'service_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'facilities_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'location_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'value_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'review' => ['required', 'string', 'max:1000'],
    ]);

    $booking = Booking::findOrFail($validated['booking_id']);

    if ($booking->status !== 'confirmed') {
        return back()->with('error', 'Review hanya bisa diberikan setelah booking selesai.');
    }

    if ($booking->review) {
        return back()->with('error', 'Anda sudah memberikan review untuk booking ini.');
    }

    Review::create([
        'booking_id' => $booking->id,
        'room_id' => $booking->room_id,
        'rating' => $validated['rating'],
        'cleanliness_rating' => $validated['cleanliness_rating'],
        'service_rating' => $validated['service_rating'],
        'facilities_rating' => $validated['facilities_rating'],
        'location_rating' => $validated['location_rating'],
        'value_rating' => $validated['value_rating'],
        'review' => $validated['review'],
        'is_published' => true,
    ]);

    return back()->with('success', 'Terima kasih atas review Anda!');
}


    public function update(Request $request, Review $review)
{
    $validated = $request->validate([
        'rating' => ['required', 'integer', 'min:1', 'max:5'],
        'cleanliness_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'service_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'facilities_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'location_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'value_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        'review' => ['required', 'string', 'max:1000'],
    ]);

    $review->update($validated);

    return back()->with('success', 'Review berhasil diperbarui.');
}

public function destroy(Review $review)
{
    $review->delete();

    return back()->with('success', 'Review berhasil dihapus.');
}

}
