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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('food_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total_amount', 8, 2);
            $table->enum('status', ['pending', 'accepted', 'preparing', 'ready', 'collected', 'completed', 'cancelled', 'rejected'])->default('pending');
            $table->timestamp('pickup_time')->nullable();
            $table->text('notes')->nullable();
            $table->text('customer_notes')->nullable();
            $table->json('history')->nullable();
            $table->enum('order_type', ['daily', 'subscription', 'custom'])->default('daily');
            $table->integer('subscription_days')->nullable();
            $table->text('custom_details')->nullable();
            $table->string('proof_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
