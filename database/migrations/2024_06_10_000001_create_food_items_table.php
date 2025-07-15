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
        Schema::create('food_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category'); // burger, tiffin, cake, snacks, etc.
            $table->decimal('price', 8, 2);
            $table->integer('available_quantity');
            $table->date('available_date');
            $table->time('available_time');
            $table->text('pickup_address');
            $table->json('photos')->nullable();
            $table->enum('status', ['active', 'inactive', 'sold_out'])->default('active');
            $table->enum('order_type', ['daily', 'subscription', 'custom'])->default('daily');
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_items');
    }
};
