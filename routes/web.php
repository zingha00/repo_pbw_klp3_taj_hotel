<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\RoomController;
use App\Http\Controllers\User\BookingController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\ReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rooms
Route::prefix('rooms')->name('rooms.')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('index');
    Route::get('/{room}', [RoomController::class, 'show'])->name('show');
});

// About & Contact
Route::view('/about', 'user.about')->name('about');
Route::view('/contact', 'user.contact')->name('contact');

// Authenticated User Routes
Route::middleware(['auth', 'role:user'])->group(function () {

    // Bookings
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/create/{room}', [BookingController::class, 'create'])->name('create');
        Route::post('/store', [BookingController::class, 'store'])->name('store');
        Route::get('/detail/{booking}', [BookingController::class, 'show'])->name('detail');
        Route::patch('/{booking}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });

    // My Reservations
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [BookingController::class, 'myReservations'])->name('index');
    });

    // Payment
    Route::prefix('payment')->name('payment.')->group(function () {
        Route::get('/{booking}', [PaymentController::class, 'index'])->name('index');
        Route::post('/process', [PaymentController::class, 'process'])->name('process');
        Route::post('/upload-proof/{payment}', [PaymentController::class, 'uploadProof'])->name('upload-proof');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-avatar', [ProfileController::class, 'updateAvatar'])->name('update-avatar');
        Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    });

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::post('/store', [ReviewController::class, 'store'])->name('store');
        Route::put('/{review}', [ReviewController::class, 'update'])->name('update');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    });

});

// Auth & Admin Routes
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
