<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'color', 'icon', 'is_active', 'description',
    ];

    public function foodItems()
    {
        return $this->belongsToMany(FoodItem::class, 'food_item_tags');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
