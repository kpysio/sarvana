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
            $table->enum('order_type', ['daily', 'subscription', 'custom'])->default('daily')->after('customer_notes');
            $table->integer('subscription_days')->nullable()->after('order_type');
            $table->text('custom_details')->nullable()->after('subscription_days');
            $table->string('proof_photo')->nullable()->after('custom_details');
            $table->enum('status', ['pending', 'accepted', 'preparing', 'ready', 'collected', 'completed', 'cancelled', 'rejected'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_type', 'subscription_days', 'custom_details', 'proof_photo']);
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
};
