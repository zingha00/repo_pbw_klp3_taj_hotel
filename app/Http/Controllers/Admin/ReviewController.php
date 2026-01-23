<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Room;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'room', 'booking']);

        // Filter by rating
        if ($request->has('rating') && $request->rating != 'all') {
            $query->where('rating', $request->rating);
        }

        // Filter by room
        if ($request->has('room_id') && $request->room_id != 'all') {
            $query->where('room_id', $request->room_id);
        }

        // Filter by status (if you want moderation)
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Search by content or user
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('review', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $reviews = $query->latest()->paginate(15);

        // Rating counts
        $ratingCounts = [
            'all' => Review::count(),
            '5' => Review::where('rating', 5)->count(),
            '4' => Review::where('rating', 4)->count(),
            '3' => Review::where('rating', 3)->count(),
            '2' => Review::where('rating', 2)->count(),
            '1' => Review::where('rating', 1)->count(),
        ];

        // Get all rooms for filter
        $rooms = Room::all();

        return view('admin.reviews.index', compact('reviews', 'ratingCounts', 'rooms'));
    }

    /**
     * Display the specified review
     */
    public function show($id)
    {
        $review = Review::with(['user', 'room', 'booking'])
            ->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve review (if moderation is enabled)
     */
    public function approve($id)
    {
        $review = Review::findOrFail($id);

        $review->update([
            'status' => 'approved',
            'moderated_at' => now(),
            'moderated_by' => auth()->id()
        ]);

        return redirect()->back()
            ->with('success', 'Review berhasil disetujui');
    }

    /**
     * Reject/Hide review
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $review = Review::findOrFail($id);

        $review->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'moderated_at' => now(),
            'moderated_by' => auth()->id()
        ]);

        // Update room rating after rejection
        $this->updateRoomRating($review->room_id);

        return redirect()->back()
            ->with('success', 'Review berhasil ditolak/disembunyikan');
    }

    /**
     * Delete review
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $roomId = $review->room_id;

        $review->delete();

        // Update room rating after deletion
        $this->updateRoomRating($roomId);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review berhasil dihapus');
    }

    /**
     * Bulk approve reviews
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        Review::whereIn('id', $validated['review_ids'])
            ->update([
                    'status' => 'approved',
                    'moderated_at' => now(),
                    'moderated_by' => auth()->id()
                ]);

        return redirect()->back()
            ->with('success', count($validated['review_ids']) . ' review berhasil disetujui');
    }

    /**
     * Bulk delete reviews
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:reviews,id'
        ]);

        $reviews = Review::whereIn('id', $validated['review_ids'])->get();
        $roomIds = $reviews->pluck('room_id')->unique();

        Review::whereIn('id', $validated['review_ids'])->delete();

        // Update room ratings
        foreach ($roomIds as $roomId) {
            $this->updateRoomRating($roomId);
        }

        return redirect()->back()
            ->with('success', count($validated['review_ids']) . ' review berhasil dihapus');
    }

    /**
     * Get review statistics
     */
    public function statistics()
    {
        $stats = [
            'total_reviews' => Review::count(),
            'average_rating' => round(Review::avg('rating'), 1),
            'five_star' => Review::where('rating', 5)->count(),
            'four_star' => Review::where('rating', 4)->count(),
            'three_star' => Review::where('rating', 3)->count(),
            'two_star' => Review::where('rating', 2)->count(),
            'one_star' => Review::where('rating', 1)->count(),
        ];

        // Top reviewed rooms
        $topReviewedRooms = Room::withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->take(5)
            ->get();

        // Best rated rooms
        $bestRatedRooms = Room::where('reviews_count', '>', 0)
            ->orderBy('rating', 'desc')
            ->take(5)
            ->get();

        return view('admin.reviews.statistics', compact('stats', 'topReviewedRooms', 'bestRatedRooms'));
    }

    /**
     * Update room rating after review changes
     */
    private function updateRoomRating($room_id)
    {
        $room = Room::find($room_id);

        if (!$room)
            return;

        // Only count approved reviews (if moderation is enabled)
        $avgRating = Review::where('room_id', $room_id)
            ->where('status', 'approved')
            ->avg('rating');

        $reviewsCount = Review::where('room_id', $room_id)
            ->where('status', 'approved')
            ->count();

        $room->update([
            'rating' => $avgRating ? round($avgRating, 1) : 0,
            'reviews_count' => $reviewsCount
        ]);
    }

    /**
     * Export reviews to CSV
     */
    public function export(Request $request)
    {
        $query = Review::with(['user', 'room']);

        if ($request->has('rating') && $request->rating != 'all') {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->get();

        $filename = 'reviews_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reviews) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Room', 'User', 'Rating', 'Review', 'Status', 'Date']);

            // Data
            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->room->name,
                    $review->user->name,
                    $review->rating,
                    $review->review,
                    $review->status ?? 'approved',
                    $review->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}