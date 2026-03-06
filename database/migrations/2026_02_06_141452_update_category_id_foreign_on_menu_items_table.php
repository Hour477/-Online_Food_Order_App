<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // -------------------------------
        // 1️⃣ menu_items: category_id → nullOnDelete
        // -------------------------------
        Schema::table('menu_items', function (Blueprint $table) {
            // Drop old foreign key
            $table->dropForeign(['category_id']);
            
            // Make column nullable
            $table->foreignId('category_id')->nullable()->change();
            
            // Add new foreign key with nullOnDelete
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->nullOnDelete();
        });

        // -------------------------------
        // 2️⃣ order_items: menu_item_id → nullOnDelete
        // -------------------------------
        Schema::table('order_items', function (Blueprint $table) {
            // Drop old foreign key for menu_item_id
            $table->dropForeign(['menu_item_id']);

            // Make column nullable
            $table->foreignId('menu_item_id')->nullable()->change();

            // Add new foreign key with nullOnDelete
            $table->foreign('menu_item_id')
                  ->references('id')
                  ->on('menu_items')
                  ->nullOnDelete();
        });

        // -------------------------------
        // 3️⃣ payments: order_id → nullOnDelete
        // -------------------------------
        Schema::table('payments', function (Blueprint $table) {
            // Drop old foreign key
            $table->dropForeign(['order_id']);

            // Make column nullable
            $table->foreignId('order_id')->nullable()->change();

            // Add new foreign key with nullOnDelete
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // -------------------------------
        // Rollback menu_items
        // -------------------------------
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreignId('category_id')->nullable(false)->change();
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->cascadeOnDelete();
        });

        // -------------------------------
        // Rollback order_items
        // -------------------------------
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['menu_item_id']);
            $table->foreignId('menu_item_id')->nullable(false)->change();
            $table->foreign('menu_item_id')
                  ->references('id')
                  ->on('menu_items')
                  ->cascadeOnDelete();
        });

        // -------------------------------
        // Rollback payments
        // -------------------------------
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->foreignId('order_id')->nullable(false)->change();
            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->cascadeOnDelete();
        });
    }
};
