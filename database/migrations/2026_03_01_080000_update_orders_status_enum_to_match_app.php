<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * App uses: pending, preparing, ready, completed, cancelled.
     * DB had:   pending, cooking, served, paid, cancelled.
     */
    public function up(): void
    {
        // Map old values to new (so existing rows stay valid)
        DB::table('orders')
            ->where('status', 'cooking')
            ->update(['status' => 'preparing']);
        DB::table('orders')
            ->where('status', 'served')
            ->update(['status' => 'ready']);
        DB::table('orders')
            ->where('status', 'paid')
            ->update(['status' => 'completed']);

        // Change ENUM to app values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'preparing', 'ready', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Map new back to old before reverting enum
        DB::table('orders')
            ->where('status', 'preparing')
            ->update(['status' => 'cooking']);
        DB::table('orders')
            ->where('status', 'ready')
            ->update(['status' => 'served']);
        DB::table('orders')
            ->where('status', 'completed')
            ->update(['status' => 'paid']);

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'cooking', 'served', 'paid', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
