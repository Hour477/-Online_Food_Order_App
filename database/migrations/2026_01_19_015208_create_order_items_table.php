<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
               // Delete order → delete order items (OK)
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            // Delete menu item → set NULL (keep order history)
            $table->foreignId('menu_item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
                
            
            $table->integer('quantity');
            $table->decimal('price',10,2);
            $table->decimal('subtotal', 10,2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
