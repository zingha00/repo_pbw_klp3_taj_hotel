<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update booking status enum to include waiting_verification
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'waiting_verification', 'confirmed', 'cancelled') DEFAULT 'pending'");
        
        // Add room_price column if not exists
        if (!Schema::hasColumn('bookings', 'room_price')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->decimal('room_price', 12, 2)->nullable()->after('total_price');
            });
        }
        
        // Add payment_method column if not exists
        if (!Schema::hasColumn('bookings', 'payment_method')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('payment_method')->nullable()->after('status');
            });
        }
        
        // Add admin_notes column if not exists
        if (!Schema::hasColumn('bookings', 'admin_notes')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->text('admin_notes')->nullable()->after('payment_method');
            });
        }
        
        // Add rejection_reason column if not exists
        if (!Schema::hasColumn('bookings', 'rejection_reason')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->text('rejection_reason')->nullable()->after('admin_notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert status enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'paid', 'completed', 'cancelled') DEFAULT 'pending'");
        
        // Remove added columns
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
            if (Schema::hasColumn('bookings', 'admin_notes')) {
                $table->dropColumn('admin_notes');
            }
            if (Schema::hasColumn('bookings', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('bookings', 'room_price')) {
                $table->dropColumn('room_price');
            }
        });
    }
};