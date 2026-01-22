<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Room;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $rooms = Room::all();

        if ($users->isEmpty() || $rooms->isEmpty()) {
            return;
        }

        $bookings = [
            
            [
                'user_id' => $users->random()->id,
                'room_id' => $rooms->random()->id,
                'booking_code' => 'BK' . date('Ymd') . '002',
                'guest_name' => 'Jane Smith',
                'guest_email' => 'jane@example.com',
                'guest_phone' => '081234567892',
                'check_in' => Carbon::now()->addDays(14),
                'check_out' => Carbon::now()->addDays(16),
                'guests' => 2,
                'rooms_count' => 1,
                'nights' => 2,
                'room_price' => 2500000,
                'total_price' => 5000000,
                'status' => 'confirmed',
            ],
            [
                'user_id' => $users->random()->id,
                'room_id' => $rooms->random()->id,
                'booking_code' => 'BK' . date('Ymd') . '003',
                'guest_name' => 'Bob Wilson',
                'guest_email' => 'bob@example.com',
                'guest_phone' => '081234567893',
                'check_in' => Carbon::now()->subDays(2),
                'check_out' => Carbon::now()->addDays(1),
                'guests' => 4,
                'rooms_count' => 1,
                'nights' => 3,
                'room_price' => 3000000,
                'total_price' => 9000000,
                'status' => 'confirmed',
            ],
        ];

        foreach ($bookings as $bookingData) {
            $booking = Booking::updateOrCreate(
                ['booking_code' => $bookingData['booking_code']],
                $bookingData
            );

            // Create payment for each booking if not exists
            if (!$booking->payment) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'payment_method' => 'bank',
                    'amount' => $bookingData['total_price'],
                    'status' => $booking->status === 'confirmed' ? 'verified' : 'pending',
                    'paid_at' => $booking->status === 'confirmed' ? now() : null,
                    'verified_at' => $booking->status === 'confirmed' ? now() : null,
                    'verified_by' => $booking->status === 'confirmed' ? User::where('role', 'admin')->first()->id : null,
                ]);
            }
        }
    }
}