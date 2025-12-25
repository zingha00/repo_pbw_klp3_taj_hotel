<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// ========== PUBLIC ROUTES ==========

// Home & Static Pages
Route::get('/', [HomeController::class, 'index'])->name('home'); // akan menampilkan home.blade.php
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Rooms (Public)
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// ========== AUTHENTICATION ROUTES ==========

// Login & Register
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Google OAuth
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========== RESERVATION ROUTES (Login Required) ==========
Route::middleware(['auth'])->group(function () {
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{id}/summary', [ReservationController::class, 'summary'])->name('reservations.summary');
    Route::post('/reservations/{id}/mark-paid', [ReservationController::class, 'markAsPaid'])->name('reservations.mark-paid');
    Route::get('/payment-success', [ReservationController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/my-reservations', [ReservationController::class, 'myReservations'])->name('reservations.my');
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
});

// ========== ADMIN ROUTES (Admin Only) ==========
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Manage Rooms (CRUD)
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{id}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');
});