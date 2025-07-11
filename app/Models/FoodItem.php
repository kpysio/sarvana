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
        'order_type',
        'expiry_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'available_date' => 'date',
        'available_time' => 'datetime',
        'photos' => 'array',
        'expiry_date' => 'date',
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

    public function isExpired()
    {
        if ($this->order_type === 'daily') {
            return $this->available_date->isPast();
        }
        if ($this->expiry_date) {
            return $this->expiry_date->isPast();
        }
        return false;
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

    public function scopeExpiringSoon($query)
    {
        $tomorrow = now()->addDay()->startOfDay();
        $end = now()->addDay()->endOfDay();
        return $query->where('status', 'active')
            ->where(function($q) use ($tomorrow, $end) {
                $q->where(function($q2) use ($tomorrow, $end) {
                    $q2->where('order_type', 'daily')
                        ->where('available_date', '>=', $tomorrow)
                        ->where('available_date', '<=', $end);
                })
                ->orWhere(function($q2) use ($tomorrow, $end) {
                    $q2->whereIn('order_type', ['subscription', 'custom'])
                        ->whereNotNull('expiry_date')
                        ->where('expiry_date', '>=', $tomorrow)
                        ->where('expiry_date', '<=', $end);
                });
            });
    }
}
