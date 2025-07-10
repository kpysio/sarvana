<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id',
        'provider_id',
        'food_item_id',
        'order_id',
        'rating',
        'comment',
        'photos',
    ];

    protected $casts = [
        'rating' => 'integer',
        'photos' => 'array',
    ];

    // Relationships
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Scopes
    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopePositive($query)
    {
        return $query->where('rating', '>=', 4);
    }

    public function scopeNegative($query)
    {
        return $query->where('rating', '<=', 2);
    }
}
