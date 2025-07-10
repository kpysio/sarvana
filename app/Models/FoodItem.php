<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'title',
        'description',
        'category',
        'price',
        'available_quantity',
        'available_date',
        'available_time',
        'pickup_address',
        'photos',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'available_date' => 'date',
        'available_time' => 'datetime',
        'photos' => 'array',
    ];

    // Relationships
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'food_item_tags');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
                    ->where('available_quantity', '>', 0);
    }
}
