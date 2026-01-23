<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_facility', function (Blueprint $table) {
            // Tambahkan foreign keys
            $table->unsignedBigInteger('room_id')->after('id');
            $table->unsignedBigInteger('room_facility_id')->after('room_id');
            
            // Buat foreign key constraints
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms')
                  ->onDelete('cascade');
                  
            $table->foreign('room_facility_id')
                  ->references('id')
                  ->on('room_facilities')
                  ->onDelete('cascade');
            
            // Buat unique constraint agar tidak ada duplikat
            $table->unique(['room_id', 'room_facility_id']);
        });
    }

    public function down(): void
    {
        Schema::table('room_facility', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropForeign(['room_facility_id']);
            $table->dropUnique(['room_id', 'room_facility_id']);
            $table->dropColumn(['room_id', 'room_facility_id']);
        });
    }
};