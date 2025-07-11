<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('abc1234'), // Master password
            'remember_token' => Str::random(10),
            'user_type' => fake()->randomElement(['customer', 'provider', 'admin']),
            'phone' => fake()->phoneNumber(),
            'postcode' => fake()->randomElement(['M1 1AA', 'M2 2BB', 'M3 3CC', 'M4 4DD', 'M5 5EE', 'M6 6FF', 'M7 7GG', 'M8 8HH']),
            'address' => fake()->address(),
            'bio' => fake()->sentence(10),
            'profile_photo' => null,
            'is_verified' => fake()->boolean(30),
            'membership_status' => fake()->randomElement(['active', 'expired', 'pending']),
            'membership_expires_at' => fake()->dateTimeBetween('now', '+1 year'),
            'rating' => fake()->randomFloat(2, 0, 5),
            'total_reviews' => fake()->numberBetween(0, 50),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'admin',
            'membership_status' => 'active',
            'membership_expires_at' => fake()->dateTimeBetween('now', '+1 year'),
            'is_verified' => true,
            'bio' => 'System administrator for Sarvana marketplace',
            'rating' => 0,
            'total_reviews' => 0,
        ]);
    }

    /**
     * Indicate that the user is a provider.
     */
    public function provider(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'provider',
            'membership_status' => 'active',
            'membership_expires_at' => fake()->dateTimeBetween('now', '+1 year'),
            'bio' => fake()->randomElement([
                'Home cook specializing in authentic Gujarati cuisine',
                'Making fresh Indian food with love for 15+ years',
                'Traditional recipes passed down through generations',
                'Vegetarian specialist - pure veg cooking only',
                'Expert in North Indian and South Indian dishes',
                'Healthy home-cooked meals for students and families',
                'Authentic street food and snacks maker',
                'Tiffin service provider - hot and fresh daily meals'
            ]),
            'rating' => fake()->randomFloat(2, 3.5, 5),
            'total_reviews' => fake()->numberBetween(5, 100),
        ]);
    }

    /**
     * Indicate that the user is a customer.
     */
    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'user_type' => 'customer',
            'membership_status' => null,
            'membership_expires_at' => null,
            'bio' => fake()->randomElement([
                'Student looking for healthy home-cooked meals',
                'Working professional who loves authentic Indian food',
                'Food lover exploring local homemade dishes',
                'Family person who appreciates good home cooking',
                'Health-conscious individual seeking fresh meals'
            ]),
            'rating' => 0,
            'total_reviews' => 0,
        ]);
    }
}
