<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Booking;
use App\Models\Payment;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share data with admin sidebar
        View::composer('components.admin-sidebar', function ($view) {
            $pendingBookings = Booking::where('status', 'pending')->count();
            $pendingPayments = Payment::where('status', 'pending')->count();
            
            $view->with([
                'pendingBookings' => $pendingBookings,
                'pendingPayments' => $pendingPayments,
            ]);
        });
    }
}