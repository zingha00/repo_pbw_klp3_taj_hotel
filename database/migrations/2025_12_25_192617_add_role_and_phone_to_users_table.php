<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom role
            $table->enum('role', ['admin', 'staff', 'customer'])->default('customer')->after('email');

            // Tambah kolom phone
            $table->string('phone')->nullable()->after('email');

            // Tambah kolom untuk Google OAuth (jika belum ada)
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('google_id');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('password');
            }

            // Make password nullable (untuk Google login)
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'google_id', 'avatar']);
        });
    }
};