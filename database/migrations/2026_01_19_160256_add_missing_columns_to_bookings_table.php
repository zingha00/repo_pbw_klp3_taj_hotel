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
        Schema::table('bookings', function (Blueprint $table) {
            // Add booking_code if not exists
            if (!Schema::hasColumn('bookings', 'booking_code')) {
                $table->string('booking_code', 20)->unique()->after('id');
            }

            // Add guest information columns
            if (!Schema::hasColumn('bookings', 'guest_name')) {
                $table->string('guest_name')->after('user_id');
            }

            if (!Schema::hasColumn('bookings', 'guest_email')) {
                $table->string('guest_email')->after('guest_name');
            }

            if (!Schema::hasColumn('bookings', 'guest_phone')) {
                $table->string('guest_phone', 20)->after('guest_email');
            }

            // Add rooms_count if not exists
            if (!Schema::hasColumn('bookings', 'rooms_count')) {
                $table->integer('rooms_count')->default(1)->after('guests');
            }

            // Add nights if not exists
            if (!Schema::hasColumn('bookings', 'nights')) {
                $table->integer('nights')->after('check_out');
            }

            // Make sure total_price exists
            if (!Schema::hasColumn('bookings', 'total_price')) {
                $table->decimal('total_price', 12, 2)->after('nights');
            }

            // Add notes column (optional)
            if (!Schema::hasColumn('bookings', 'notes')) {
                $table->text('notes')->nullable()->after('total_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'booking_code',
                'guest_name',
                'guest_email',
                'guest_phone',
                'rooms_count',
                'nights',
                'notes',
            ]);
        });
    }
};