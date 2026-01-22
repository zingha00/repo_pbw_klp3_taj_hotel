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
        Schema::create('room_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('room_facility_id')->constrained('room_facilities')->onDelete('cascade');
            $table->timestamps();
            
            // Hindari duplikasi: satu kamar tidak bisa punya fasilitas yang sama 2x
            $table->unique(['room_id', 'room_facility_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_facility');
    }
};