<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Order;
use App\Models\User;
use App\Models\FoodItem;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foodItem = FoodItem::factory()->create();
        $quantity = fake()->numberBetween(1, 3);
        
        return [
            'customer_id' => User::factory()->customer(),
            'provider_id' => $foodItem->provider_id,
            'food_item_id' => $foodItem->id,
            'quantity' => $quantity,
            'total_amount' => $foodItem->price * $quantity,
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'pickup_time' => fake()->dateTimeBetween('now', '+2 days'),
            'notes' => fake()->randomElement([
                'Please call when ready',
                'Extra spicy please',
                'No onions',
                'Pack separately',
                'Will collect at 7 PM',
                null
            ]),
            'customer_notes' => fake()->randomElement([
                'Thank you for the delicious food!',
                'Looking forward to trying this',
                'Please ensure it\'s fresh',
                null
            ]),
        ];
    }
}
