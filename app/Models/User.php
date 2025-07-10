<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'postcode',
        'address',
        'bio',
        'profile_photo',
        'is_verified',
        'membership_status',
        'membership_expires_at',
        'rating',
        'total_reviews',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'membership_expires_at' => 'datetime',
            'rating' => 'decimal:2',
            'is_verified' => 'boolean',
        ];
    }

    // Relationships
    public function foodItems()
    {
        return $this->hasMany(FoodItem::class, 'provider_id');
    }

    public function customerOrders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function providerOrders()
    {
        return $this->hasMany(Order::class, 'provider_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function providerReviews()
    {
        return $this->hasMany(Review::class, 'provider_id');
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'provider_id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    // Helper methods
    public function isProvider()
    {
        return $this->user_type === 'provider';
    }

    public function isCustomer()
    {
        return $this->user_type === 'customer';
    }

    public function hasActiveMembership()
    {
        return $this->membership_status === 'active' && 
               $this->membership_expires_at > now();
    }
}
