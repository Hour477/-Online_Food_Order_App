<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (config('database.default') === 'mysql') {
            // First, convert enum to varchar to allow modification
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(20)");
            
            // Then add the new enum with khqr included
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash', 'card', 'qr', 'khqr')");
        } else {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('payment_method')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (config('database.default') === 'mysql') {
            // Revert back to original enum
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method VARCHAR(20)");
            DB::statement("ALTER TABLE payments MODIFY COLUMN payment_method ENUM('cash', 'card', 'qr')");
        } else {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('payment_method')->change();
            });
        }
    }
};
