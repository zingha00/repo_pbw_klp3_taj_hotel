<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('rating'); // 1-5 stars
            $table->text('review'); // Ulasan text
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('moderated_at')->nullable();
            $table->unsignedBigInteger('moderated_by')->nullable();
            $table->timestamps();
            
            // Index untuk performa
            $table->index('booking_id');
            $table->index('room_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};