<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration safely modifies the existing payments table
     */
    public function up(): void
    {
        // First, add new columns
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('created_at');
            }
            
            if (!Schema::hasColumn('payments', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()
                      ->after('created_at')
                      ->constrained('users')
                      ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('payments', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('created_at');
            }
            
            if (!Schema::hasColumn('payments', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()
                      ->after('created_at')
                      ->constrained('users')
                      ->onDelete('set null');
            }
            
            if (!Schema::hasColumn('payments', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_method');
            }
        });
        
        // Then handle the enum column rename with proper approach
        if (Schema::hasColumn('payments', 'payment_status') && !Schema::hasColumn('payments', 'status')) {
            // Add new status column
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('status', ['pending', 'verified', 'failed'])->default('pending')->after('amount');
            });
            
            // Copy data from old column to new column
            DB::statement('UPDATE payments SET status = payment_status');
            
            // Drop old column
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the enum column change
        if (Schema::hasColumn('payments', 'status') && !Schema::hasColumn('payments', 'payment_status')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->enum('payment_status', ['pending', 'verified', 'failed'])->default('pending')->after('amount');
            });
            
            DB::statement('UPDATE payments SET payment_status = status');
            
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
        
        // Remove added columns
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }
            
            if (Schema::hasColumn('payments', 'rejected_by')) {
                $table->dropForeign(['rejected_by']);
                $table->dropColumn('rejected_by');
            }
            
            if (Schema::hasColumn('payments', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            
            if (Schema::hasColumn('payments', 'verified_by')) {
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
            }
            
            if (Schema::hasColumn('payments', 'payment_proof')) {
                $table->dropColumn('payment_proof');
            }
        });
    }
};