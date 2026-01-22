<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Cek dulu apakah kolom stock sudah ada
            if (!Schema::hasColumn('rooms', 'stock')) {
                $table->integer('stock')->default(1)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            // Cek dulu sebelum drop
            if (Schema::hasColumn('rooms', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }
};