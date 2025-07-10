<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;
use App\Models\User;
use App\Models\FoodItem;
use App\Models\Order;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    private $indianFoodReviews = [
        'Absolutely delicious! Reminded me of my grandmother\'s cooking.',
        'Fresh and authentic taste. Will definitely order again.',
        'Perfect spice level and great portion size.',
        'The best homemade Indian food in the area!',
        'Excellent quality and very reasonably priced.',
        'Food was ready on time and tasted amazing.',
        'Authentic flavors, just like home cooking.',
        'Great value for money. Highly recommended!',
        'Loved the traditional preparation method.',
        'Fresh ingredients and perfect seasoning.',
        'Outstanding taste and presentation.',
        'Very satisfied with the quality and service.',
        'The food was still warm when I collected it.',
        'Genuine homemade taste, not restaurant style.',
        'Perfect for students - affordable and filling.',
        'The thepla was soft and flavorful.',
        'Best biryani I\'ve had in ages!',
        'Exactly what I was craving for.',
        'Clean preparation and hygienic packaging.',
        'Will become a regular customer for sure!'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reviewer_id' => User::factory()->customer(),
            'provider_id' => User::factory()->provider(),
            'food_item_id' => FoodItem::factory(),
            'order_id' => Order::factory(),
            'rating' => fake()->numberBetween(3, 5), // Mostly positive reviews
            'comment' => fake()->randomElement($this->indianFoodReviews),
            'photos' => fake()->boolean(30) ? json_encode([
                'https://via.placeholder.com/300x200/FF6B6B/FFFFFF?text=Food+Review',
                'https://via.placeholder.com/300x200/4ECDC4/FFFFFF?text=Delicious'
            ]) : null,
        ];
    }

    /**
     * Indicate that the review is positive.
     */
    public function positive(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->numberBetween(4, 5),
            'comment' => fake()->randomElement([
                'Outstanding food! Exceeded my expectations.',
                'Perfect authentic taste. Highly recommended!',
                'Fresh, delicious, and great value for money.',
                'Best homemade Indian food in the neighborhood.',
                'Amazing quality and perfect spice level.'
            ]),
        ]);
    }
}
