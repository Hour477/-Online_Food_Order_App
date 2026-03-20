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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('khqr_md5')->nullable()->after('status');
            $table->string('khqr_string')->nullable()->after('khqr_md5');
            $table->string('khqr_transaction_id')->nullable()->after('khqr_string');
            $table->timestamp('khqr_expires_at')->nullable()->after('khqr_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['khqr_md5', 'khqr_string', 'khqr_transaction_id', 'khqr_expires_at']);
        });
    }
};
