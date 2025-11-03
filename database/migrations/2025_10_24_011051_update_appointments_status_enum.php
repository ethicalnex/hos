<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old enum column
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        // Add new enum column with all required statuses
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', [
                'scheduled', 
                'confirmed', 
                'completed', 
                'cancelled', 
                'no_show',
                'pending_payment'  // ← ADD THIS
            ])->default('scheduled');
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('status', [
                'scheduled', 
                'confirmed', 
                'completed', 
                'cancelled', 
                'no_show'
            ])->default('scheduled');
        });
    }
};