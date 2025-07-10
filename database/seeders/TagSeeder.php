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
            ['name' => 'No Onion No Garlic', 'category' => 'food_type', 'color' => '#10B981', 'icon' => '🧄'],
            ['name' => 'Veggy', 'category' => 'food_type', 'color' => '#059669', 'icon' => '🥬'],
            ['name' => 'Non-veg', 'category' => 'food_type', 'color' => '#DC2626', 'icon' => '🍖'],
            
            // Festival
            ['name' => 'Diwali', 'category' => 'festival', 'color' => '#F59E0B', 'icon' => '🪔'],
            ['name' => 'Rakhi', 'category' => 'festival', 'color' => '#EF4444', 'icon' => '🎀'],
            ['name' => 'Uttarayan', 'category' => 'festival', 'color' => '#3B82F6', 'icon' => '🪁'],
            
            // Seasonal
            ['name' => 'Summer', 'category' => 'seasonal', 'color' => '#F97316', 'icon' => '☀️'],
            ['name' => 'Winter', 'category' => 'seasonal', 'color' => '#06B6D4', 'icon' => '❄️'],
            
            // Food Origin
            ['name' => 'Gujarati', 'category' => 'food_origin', 'color' => '#8B5CF6', 'icon' => '🏛️'],
            ['name' => 'South Indian', 'category' => 'food_origin', 'color' => '#EC4899', 'icon' => '🌴'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
} 