<?php
// database/seeders/TagSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            // Food Type
            ['name' => 'No Onion No Garlic', 'category' => 'food_type', 'color' => '#10B981', 'icon' => '🧄', 'description' => 'Food prepared without onion and garlic'],
            ['name' => 'Veggy', 'category' => 'food_type', 'color' => '#059669', 'icon' => '🥬', 'description' => 'Vegetarian food items'],
            ['name' => 'Non-veg', 'category' => 'food_type', 'color' => '#DC2626', 'icon' => '🍖', 'description' => 'Non-vegetarian food items'],
            ['name' => 'Vegan', 'category' => 'food_type', 'color' => '#059669', 'icon' => '🌱', 'description' => 'Vegan food items without any animal products'],
            
            // Festival
            ['name' => 'Diwali', 'category' => 'festival', 'color' => '#F59E0B', 'icon' => '🪔', 'description' => 'Special food for Diwali celebrations'],
            ['name' => 'Rakhi', 'category' => 'festival', 'color' => '#EF4444', 'icon' => '🎀', 'description' => 'Traditional food for Raksha Bandhan'],
            ['name' => 'Uttarayan', 'category' => 'festival', 'color' => '#3B82F6', 'icon' => '🪁', 'description' => 'Special food for Uttarayan/Makar Sankranti'],
            ['name' => 'Christmas', 'category' => 'festival', 'color' => '#DC2626', 'icon' => '🎄', 'description' => 'Christmas celebration food'],
            
            // Seasonal
            ['name' => 'Summer', 'category' => 'seasonal', 'color' => '#F97316', 'icon' => '☀️', 'description' => 'Refreshing food perfect for summer'],
            ['name' => 'Winter', 'category' => 'seasonal', 'color' => '#06B6D4', 'icon' => '❄️', 'description' => 'Warming food ideal for winter'],
            ['name' => 'Monsoon', 'category' => 'seasonal', 'color' => '#3B82F6', 'icon' => '🌧️', 'description' => 'Comfort food for monsoon season'],
            
            // Food Origin
            ['name' => 'Gujarati', 'category' => 'food_origin', 'color' => '#8B5CF6', 'icon' => '🏛️', 'description' => 'Traditional Gujarati cuisine'],
            ['name' => 'South Indian', 'category' => 'food_origin', 'color' => '#EC4899', 'icon' => '🌴', 'description' => 'Authentic South Indian dishes'],
            ['name' => 'North Indian', 'category' => 'food_origin', 'color' => '#F59E0B', 'icon' => '🏔️', 'description' => 'Traditional North Indian cuisine'],
            ['name' => 'Chinese', 'category' => 'food_origin', 'color' => '#DC2626', 'icon' => '🏮', 'description' => 'Chinese cuisine and fusion dishes'],
            
            // Dietary
            ['name' => 'Gluten-free', 'category' => 'dietary', 'color' => '#10B981', 'icon' => '🌾', 'description' => 'Food without gluten for celiac patients'],
            ['name' => 'Sugar-free', 'category' => 'dietary', 'color' => '#8B5CF6', 'icon' => '🍯', 'description' => 'Food without added sugar'],
            ['name' => 'Low-carb', 'category' => 'dietary', 'color' => '#059669', 'icon' => '🥑', 'description' => 'Low carbohydrate food options'],
            
            // Cuisine
            ['name' => 'Italian', 'category' => 'cuisine', 'color' => '#DC2626', 'icon' => '🇮🇹', 'description' => 'Authentic Italian cuisine'],
            ['name' => 'Mexican', 'category' => 'cuisine', 'color' => '#F59E0B', 'icon' => '🇲🇽', 'description' => 'Traditional Mexican dishes'],
            ['name' => 'Thai', 'category' => 'cuisine', 'color' => '#3B82F6', 'icon' => '🇹🇭', 'description' => 'Thai cuisine with authentic flavors'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
} 