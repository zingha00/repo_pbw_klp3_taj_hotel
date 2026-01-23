<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('room_images', function (Blueprint $table) {
            // Tambahkan kolom yang belum ada
            $table->string('image_path')->after('room_id');
            $table->integer('order')->default(0)->after('image_path');
            $table->boolean('is_primary')->default(false)->after('order');
        });
    }

    public function down(): void
    {
        Schema::table('room_images', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'order', 'is_primary']);
        });
    }
};