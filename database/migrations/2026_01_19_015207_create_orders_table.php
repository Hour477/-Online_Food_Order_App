<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_no')->unique();

            $table->enum('order_type', ['dine_in', 'takeaway', 'delivery'])
                ->default('dine_in');

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('table_id')
                ->nullable()
                ->constrained('tables')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            // $table->enum('status', [
            //     'pending',
            //     'completed',
            //     'delivered', // customer has received the order
            //     'cancelled',
            //     'refunded'
            // ])->default('pending')->change();
            $table->string('status')->default('pending');
            // Add columns for order totals
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->softDeletes()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
