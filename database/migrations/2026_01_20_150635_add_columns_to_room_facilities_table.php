<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_facilities', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('icon')->nullable()->after('name'); // Untuk icon/emoji fasilitas
            $table->text('description')->nullable()->after('icon'); // Opsional
        });
    }

    public function down(): void
    {
        Schema::table('room_facilities', function (Blueprint $table) {
            $table->dropColumn(['name', 'icon', 'description']);
        });
    }
};