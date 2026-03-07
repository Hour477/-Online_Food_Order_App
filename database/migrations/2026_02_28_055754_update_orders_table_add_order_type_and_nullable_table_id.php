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
         Schema::table('orders', function (Blueprint $table) {
            // Make table_id nullable
            $table->foreignId('table_id')
                  ->nullable()
                  ->change();

            // Add order_type column
            $table->enum('order_type', ['dine_in', 'takeout', 'delivery'])
                  ->default('dine_in')
                  ->after('customer_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('table_id')
                  ->nullable(false)
                  ->change();
            $table->dropColumn('order_type');
        });
    }
};
