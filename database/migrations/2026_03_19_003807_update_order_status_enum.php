<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Change column to VARCHAR temporarily to avoid enum restrictions
        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        
        // Step 2: Map old statuses to new ones
        // Map 'ready' to 'delivered' (ready for delivery)
        DB::statement("UPDATE orders SET status = 'delivered' WHERE status = 'ready'");
        // Keep other existing statuses as they are
        
        // Step 3: Change to new ENUM with all statuses
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'confirmed', 'delivered', 'completed', 'refunded', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original status enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'completed', 'delivered', 'cancelled', 'refunded') DEFAULT 'pending'");
    }
};
