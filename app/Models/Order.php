<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'provider_id',
        'food_item_id',
        'quantity',
        'total_amount',
        'status',
        'pickup_time',
        'notes',
        'customer_notes',
        'order_type',
        'subscription_days',
        'custom_details',
        'proof_photo',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'pickup_time' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY = 'ready';
    const STATUS_COLLECTED = 'collected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    // Status helpers
    public function isPending() { return $this->status === self::STATUS_PENDING; }
    public function isAccepted() { return $this->status === self::STATUS_ACCEPTED; }
    public function isPreparing() { return $this->status === self::STATUS_PREPARING; }
    public function isReady() { return $this->status === self::STATUS_READY; }
    public function isCollected() { return $this->status === self::STATUS_COLLECTED; }
    public function isCompleted() { return $this->status === self::STATUS_COMPLETED; }
    public function isCancelled() { return $this->status === self::STATUS_CANCELLED; }
    public function isRejected() { return $this->status === self::STATUS_REJECTED; }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
