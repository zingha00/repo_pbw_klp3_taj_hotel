<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('room_number')->unique();
            $table->string('type'); // UBAH: Gunakan string biasa, bukan enum
            $table->decimal('price', 10, 2);
            $table->integer('capacity')->default(1);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->json('facilities')->nullable();
            $table->decimal('length', 5, 2)->nullable();
            $table->decimal('width', 5, 2)->nullable();
            $table->string('status')->default('available'); // UBAH: Gunakan string biasa
            $table->boolean('available')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};