<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoomController;

Route::get('/', function () {
    return view('welcome');
});

//Home & Static Pages
Route::get(uri: '/',action: [HomeController::class,'index'])->name(name: 'home');
Route::get(uri: '/about',action: [HomeController::class, 'about'])->name(name: 'about');
Route::get(uri:'/contact', action:[HomeController::class, 'contact'])->name(name:'contact');

//Rooms (public - semua bisa akses)
Route::get(uri:'/rooms', action:[RoomController::class, 'index'])->name(name: 'rooms.index');
Route::get(uri: '/rooms/{room}', action: [RoomController::class, 'show'])->name(name: 'rooms.show');

//=========AUTHENTICATION ROUTES=========

//Login & Register
Route::get(uri: '/login', action: [AuthController::class, 'showLoginFrom'])->name(name: 'login');
Route::post(uri:'/login', action: [AuthController::class, 'login']);
Route::get(uri:'/register', action: [AuthController::class, 'showRegisterFrom'])->name(name: 'register');
Route::post(uri:'/register', action: [AuthController::class, 'register']);

//Goole OAuth
Route::get(uri: '/auth/google', action: [AuthController::class, 'redirectToGoogle'])->name(name: 'auth.google');
Route::get(uri: '/auth/googlecallback', action: [AuthController::class, 'handleGoogleCallback']);

//logout
Route::post(uri:'/logout', action: [AuthController::class, 'logout'])->name(name: 'logout');

//==========RESERVATION ROUTES(Login Required)===========

Route::middleware(middleware: ['auth'])->group(callback:function():void {

    //Booking & Reservations

    Route::post(uri:'/reservation', action: [ReservationController::class, 'store'])->name(name: 'reservation.store');
    Route::get(uri:'/reservation/{id}/summary', action: [ReservationControlle::class, 'summary'])->name(name: 'reservation.summary');
    Route::post(uri:'/reservations/{id}/mark-paid', action: [ReservationController::class, 'markAspaid'])->name(name: 'reservations.mark-paid');
    Route::get(uri:'/payment-success', action:[ReservationController::class, 'paymentSuccess'])->name(name: 'payment.success');
    Route::get(uri:'/my-reservation', action: [ReservationController::class, 'myReservations'])->name(name: 'reservations.my');
    Route::delete(uri:'/reservation/{id}', action: [ReservatiionController::class, 'destroy'])->name(name: 'reservations.destroy');
});

//==========ADMIN ROUTES (Admin Only)===========

Route::middleware(middleware: ['auth'])->prefix(prefix: 'admin')->name(value: 'admin.')->group(callback:function (): void {

    //Dashboard

    Route::get(uri: '/dashboard', action: [AdminController::class, 'dashboard'])->name(name: 'dashboard');

    //Manage Rooms (CRUD)

    Route::get(uri:'/rooms/create', action: [RoomController::class, 'create'])->name(name: 'rooms.create');
    Route::post(uri:'/rooms', action: [RoomController::class, 'store'])->name(name: 'rooms.store');
    Route::get(uri:'/rooms/{id}/edit', action: [RoomController::class, 'edit'])->name(name: 'rooms.edit');
    Route::put(uri:'/rooms/{id}', action: [RoomController::class, 'update'])->name(name: 'rooms.update');
    Route::delete(uri:'/rooms/{id}', action: [RoomControlller::class, 'destroy'])->name(name: 'rooms.destroy');
});