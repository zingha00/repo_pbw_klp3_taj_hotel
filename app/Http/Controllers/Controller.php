<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Success response method
     */
    protected function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    /**
     * Error response method
     */
    protected function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Validation error response
     */
    protected function sendValidationError($validator)
    {
        return $this->sendError('Validation Error.', $validator->errors(), 422);
    }

    /**
     * Upload image helper
     */
    protected function uploadImage($file, $path = 'uploads', $disk = 'public')
    {
        if (!$file) {
            return null;
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs($path, $filename, $disk);

        return $filePath;
    }

    /**
     * Delete image helper
     */
    protected function deleteImage($path, $disk = 'public')
    {
        if (!$path) {
            return false;
        }

        if (\Storage::disk($disk)->exists($path)) {
            return \Storage::disk($disk)->delete($path);
        }

        return false;
    }

    /**
     * Format rupiah currency
     */
    protected function formatRupiah($number)
    {
        return 'Rp ' . number_format($number, 0, ',', '.');
    }

    /**
     * Generate unique code
     */
    protected function generateUniqueCode($prefix = '', $length = 8)
    {
        return $prefix . strtoupper(\Str::random($length));
    }

    /**
     * Calculate date difference in days
     */
    protected function dateDiffInDays($startDate, $endDate)
    {
        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        return $start->diffInDays($end);
    }

    /**
     * Check if date range overlaps
     */
    protected function dateRangeOverlaps($start1, $end1, $start2, $end2)
    {
        return ($start1 <= $end2) && ($end1 >= $start2);
    }

    /**
     * Paginate collection
     */
    protected function paginateCollection($collection, $perPage = 15)
    {
        $page = request()->get('page', 1);
        $offset = ($page - 1) * $perPage;

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->slice($offset, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
}