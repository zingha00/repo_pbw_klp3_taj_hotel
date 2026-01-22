<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20)->unique();
            
            // User who made the booking
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Guest information
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone', 20);
            
            // Room information
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            
            // Booking details
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('nights');
            $table->integer('guests')->default(1);
            $table->integer('rooms_count')->default(1);
            
            // Pricing
            $table->decimal('total_price', 12, 2);
            
            // Status: pending, confirmed, paid, completed, cancelled
            $table->enum('status', ['pending', 'confirmed', 'paid', 'completed', 'cancelled'])
                  ->default('pending');
            
            // Additional notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};