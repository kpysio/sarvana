<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\FoodItem;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FoodItem>
 */
class FoodItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FoodItem::class;

    private $indianFoodItems = [
        // Rice & Biryani
        ['name' => 'Vegetable Biryani', 'category' => 'rice', 'price' => [8, 12], 'desc' => 'Aromatic basmati rice cooked with mixed vegetables and traditional spices'],
        ['name' => 'Chicken Biryani', 'category' => 'rice', 'price' => [10, 15], 'desc' => 'Fragrant basmati rice layered with spiced chicken and fried onions'],
        ['name' => 'Mutton Biryani', 'category' => 'rice', 'price' => [12, 18], 'desc' => 'Rich and flavorful mutton biryani with tender meat and aromatic rice'],
        ['name' => 'Jeera Rice', 'category' => 'rice', 'price' => [4, 6], 'desc' => 'Simple cumin flavored basmati rice, perfect with curry'],
        ['name' => 'Rice-Dal Combo', 'category' => 'tiffin', 'price' => [5, 8], 'desc' => 'Complete meal with steamed rice, dal, and pickle'],

        // Bread & Roti
        ['name' => 'Fresh Roti (5 pieces)', 'category' => 'bread', 'price' => [3, 5], 'desc' => 'Soft, freshly made wheat rotis, perfect with any curry'],
        ['name' => 'Gujarati Thepla (6 pieces)', 'category' => 'bread', 'price' => [4, 6], 'desc' => 'Spiced flatbread with fenugreek leaves, great for travel'],
        ['name' => 'Methi Thepla (8 pieces)', 'category' => 'bread', 'price' => [5, 7], 'desc' => 'Traditional Gujarati flatbread with fresh fenugreek'],
        ['name' => 'Butter Naan (4 pieces)', 'category' => 'bread', 'price' => [6, 8], 'desc' => 'Soft, buttery naan bread baked to perfection'],

        // Snacks & Street Food
        ['name' => 'Pani Puri (6 pieces)', 'category' => 'snacks', 'price' => [3, 5], 'desc' => 'Crispy puris with spicy mint water and tangy chutneys'],
        ['name' => 'Bhel Puri', 'category' => 'snacks', 'price' => [4, 6], 'desc' => 'Mumbai street food with puffed rice, sev, and chutneys'],
        ['name' => 'Papdi Chaat', 'category' => 'snacks', 'price' => [5, 7], 'desc' => 'Crispy papdi topped with yogurt, chutneys, and spices'],
        ['name' => 'Samosa (4 pieces)', 'category' => 'snacks', 'price' => [4, 6], 'desc' => 'Golden fried pastry stuffed with spiced potatoes'],
        ['name' => 'Dhokla (6 pieces)', 'category' => 'snacks', 'price' => [5, 7], 'desc' => 'Steamed Gujarati sponge cake made from gram flour'],
        ['name' => 'Kachori (4 pieces)', 'category' => 'snacks', 'price' => [4, 6], 'desc' => 'Deep-fried pastry stuffed with spiced lentils'],

        // South Indian
        ['name' => 'Masala Dosa', 'category' => 'south_indian', 'price' => [6, 8], 'desc' => 'Crispy crepe filled with spiced potato curry, served with sambhar'],
        ['name' => 'Plain Dosa', 'category' => 'south_indian', 'price' => [4, 6], 'desc' => 'Traditional South Indian crepe served with coconut chutney'],
        ['name' => 'Uttapam', 'category' => 'south_indian', 'price' => [5, 7], 'desc' => 'Thick pancake topped with vegetables and served with chutney'],
        ['name' => 'Idli Sambhar (4 pieces)', 'category' => 'south_indian', 'price' => [5, 7], 'desc' => 'Steamed rice cakes served with lentil curry'],

        // Gujarati Specialties
        ['name' => 'Gujarati Thali', 'category' => 'tiffin', 'price' => [12, 18], 'desc' => 'Complete Gujarati meal with roti, rice, dal, vegetables, and sweet'],
        ['name' => 'Nadiyadi Chavanu', 'category' => 'snacks', 'price' => [6, 9], 'desc' => 'Traditional Gujarati snack mix with various crunchy ingredients'],
        ['name' => 'Handvo', 'category' => 'snacks', 'price' => [5, 8], 'desc' => 'Savory cake made from mixed lentils and vegetables'],
        ['name' => 'Khaman', 'category' => 'snacks', 'price' => [4, 6], 'desc' => 'Soft and spongy steamed gram flour cake'],

        // Pizza & Fusion
        ['name' => 'Veggie Pizza (Medium)', 'category' => 'pizza', 'price' => [8, 12], 'desc' => 'Homemade pizza with fresh vegetables and cheese'],
        ['name' => 'Margherita Pizza', 'category' => 'pizza', 'price' => [7, 10], 'desc' => 'Classic pizza with tomato, mozzarella, and basil'],
        ['name' => 'Paneer Tikka Pizza', 'category' => 'pizza', 'price' => [9, 13], 'desc' => 'Fusion pizza with spiced paneer and Indian flavors'],

        // Tiffin & Meals
        ['name' => 'Daily Tiffin Service', 'category' => 'tiffin', 'price' => [6, 10], 'desc' => 'Fresh home-cooked meal with roti, rice, dal, and vegetables'],
        ['name' => 'Student Tiffin', 'category' => 'tiffin', 'price' => [5, 8], 'desc' => 'Affordable, nutritious meal perfect for students'],
        ['name' => 'Executive Tiffin', 'category' => 'tiffin', 'price' => [8, 12], 'desc' => 'Premium meal with variety of dishes for working professionals'],
        ['name' => 'Kathiyawadi Thali', 'category' => 'tiffin', 'price' => [10, 15], 'desc' => 'Traditional Kathiyawadi meal with authentic flavors'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $foodItem = fake()->randomElement($this->indianFoodItems);
        $priceRange = $foodItem['price'];
        
        return [
            'provider_id' => User::factory()->provider(),
            'title' => $foodItem['name'],
            'description' => $foodItem['desc'],
            'category' => $foodItem['category'],
            'price' => fake()->randomFloat(2, $priceRange[0], $priceRange[1]),
            'available_quantity' => fake()->numberBetween(2, 20),
            'available_date' => fake()->dateTimeBetween('now', '+7 days')->format('Y-m-d'),
            'available_time' => fake()->time('H:i'),
            'pickup_address' => fake()->address(),
            'photos' => json_encode([
                'https://via.placeholder.com/400x300/FF6B6B/FFFFFF?text=' . urlencode($foodItem['name']),
                'https://via.placeholder.com/400x300/4ECDC4/FFFFFF?text=Food+Photo'
            ]),
            'status' => fake()->randomElement(['active', 'active', 'active', 'inactive']), // 75% active
        ];
    }

    /**
     * Indicate that the food item is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'available_date' => fake()->dateTimeBetween('now', '+3 days')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the food item belongs to a specific category.
     */
    public function category(string $category): static
    {
        $categoryItems = array_filter($this->indianFoodItems, fn($item) => $item['category'] === $category);
        $foodItem = fake()->randomElement($categoryItems);
        
        return $this->state(fn (array $attributes) => [
            'title' => $foodItem['name'],
            'description' => $foodItem['desc'],
            'category' => $foodItem['category'],
            'price' => fake()->randomFloat(2, $foodItem['price'][0], $foodItem['price'][1]),
        ]);
    }
}
