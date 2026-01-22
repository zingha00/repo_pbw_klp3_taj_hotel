<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HotelProfileController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AnalyticsController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes untuk admin panel dengan middleware auth dan role:admin
|
*/

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard/chart-data', [DashboardController::class, 'chartData']);
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    Route::get('/api/stats', [DashboardController::class, 'getStats'])->name('api.stats');

    // Admin Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update-avatar');
    });

    // Hotel Profile
    Route::prefix('hotel')->name('hotel.')->group(function () {
        Route::get('/', [HotelProfileController::class, 'index'])->name('index');
        Route::put('/update', [HotelProfileController::class, 'update'])->name('update');
        Route::post('/upload-logo', [HotelProfileController::class, 'uploadLogo'])->name('upload-logo');
        Route::post('/upload-photos', [HotelProfileController::class, 'uploadPhotos'])
            ->name('upload-photos');
        Route::delete('/delete-photo/{index}', [HotelProfileController::class, 'deletePhoto'])->name('delete-photo');
    });

    // Rooms Management
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [RoomController::class, 'index'])->name('index');
        Route::get('/create', [RoomController::class, 'create'])->name('create');
        Route::post('/', [RoomController::class, 'store'])->name('store');
        Route::get('/{room}', [RoomController::class, 'show'])->name('show');
        Route::get('/{room}/edit', [RoomController::class, 'edit'])->name('edit');
        Route::put('/{room}', [RoomController::class, 'update'])->name('update');
        Route::delete('/{room}', [RoomController::class, 'destroy'])->name('destroy');
        Route::post('/{room}/toggle-status', [RoomController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/{room}/upload-images', [RoomController::class, 'uploadImages'])->name('upload-images');
        Route::get('/filter', [RoomController::class, 'filter'])->name('filter');
        Route::post('/check-availability', [RoomController::class, 'checkAvailability'])->name('check-availability');
    });

    // Bookings Management
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/{id}', [BookingController::class, 'show'])->name('show');
        Route::patch('/{id}/status', [BookingController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{id}/confirm', [BookingController::class, 'confirm'])->name('confirm');
        Route::post('/{id}/reject', [BookingController::class, 'reject'])->name('reject');
        Route::post('/{id}/cancel', [BookingController::class, 'cancel'])->name('cancel');
        Route::delete('/{id}', [BookingController::class, 'destroy'])->name('destroy');
        Route::delete('/{id}/force', [BookingController::class, 'forceDestroy'])->name('force-destroy');
        Route::post('/{id}/restore', [BookingController::class, 'restore'])->name('restore');
    });

    // Payments Management
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::put('/{payment}/verify', [PaymentController::class, 'verify'])->name('verify');
        Route::put('/{payment}/reject', [PaymentController::class, 'reject'])->name('reject');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
    });

    // Users Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::put('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::put('/{user}/change-role', [UserController::class, 'changeRole'])->name('change-role');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Reviews Management
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::post('/{review}/reply', [ReviewController::class, 'reply'])->name('reply');
        Route::put('/{review}/toggle-publish', [ReviewController::class, 'togglePublish'])->name('toggle-publish');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/generate', [ReportController::class, 'generate'])->name('generate');
        Route::get('/export-pdf', [ReportController::class, 'exportPDF'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
    });

    // Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/cancellations', [AnalyticsController::class, 'cancellations'])->name('cancellations');
        Route::get('/cancellations/data', [AnalyticsController::class, 'getCancellationData'])->name('cancellations.data');
    });

});