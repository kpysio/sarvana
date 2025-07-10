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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['customer', 'provider'])->default('customer');
            $table->string('phone')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->text('address')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->enum('membership_status', ['active', 'expired', 'pending'])->nullable();
            $table->timestamp('membership_expires_at')->nullable();
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_reviews')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type',
                'phone',
                'postcode',
                'address',
                'bio',
                'profile_photo',
                'is_verified',
                'membership_status',
                'membership_expires_at',
                'rating',
                'total_reviews'
            ]);
        });
    }
};
