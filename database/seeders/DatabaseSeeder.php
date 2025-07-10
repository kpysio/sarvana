<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\FoodItem;
use App\Models\Order;
use App\Models\Review;
use App\Models\Follower;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@sarvana.com',
            'password' => bcrypt('abc1234'),
            'user_type' => 'provider',
            'membership_status' => 'active',
            'membership_expires_at' => now()->addYear(),
            'is_verified' => true,
        ]);

        // Create test users
        User::factory()->create([
            'name' => 'Test Provider',
            'email' => 'provider@test.com',
            'password' => bcrypt('abc1234'),
            'user_type' => 'provider',
            'membership_status' => 'active',
            'membership_expires_at' => now()->addYear(),
            'is_verified' => true,
        ]);

        User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('abc1234'),
            'user_type' => 'customer',
        ]);

        // Create providers with Indian names
        $providers = User::factory()
            ->count(15)
            ->provider()
            ->create([
                'name' => fake()->randomElement([
                    'Priya Patel', 'Meera Shah', 'Anjali Gupta', 'Kavita Sharma', 'Ritu Mehta',
                    'Sunita Joshi', 'Nisha Agrawal', 'Pooja Desai', 'Sita Pandey', 'Geeta Trivedi',
                    'Asha Kulkarni', 'Radha Iyer', 'Lata Nair', 'Mala Reddy', 'Sudha Rao'
                ])
            ]);

        // Create customers
        $customers = User::factory()
            ->count(25)
            ->customer()
            ->create();

        // Create food items for each provider
        foreach ($providers as $provider) {
            FoodItem::factory()
                ->count(fake()->numberBetween(3, 8))
                ->create([
                    'provider_id' => $provider->id,
                    'pickup_address' => $provider->address,
                ]);
        }

        // Create orders
        $orders = Order::factory()
            ->count(50)
            ->create();

        // Create reviews for completed orders
        foreach ($orders->where('status', 'completed') as $order) {
            Review::factory()->create([
                'reviewer_id' => $order->customer_id,
                'provider_id' => $order->provider_id,
                'food_item_id' => $order->food_item_id,
                'order_id' => $order->id,
            ]);
        }

        // Create some additional reviews
        Review::factory()
            ->count(30)
            ->create();

        // Create followers
        Follower::factory()
            ->count(40)
            ->create();

        $this->call(TagSeeder::class);
    }
}
