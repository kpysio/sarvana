<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FoodItem;
use Carbon\Carbon;
use App\Notifications\FoodItemExpiringSoon;

class ExpireFoodItems extends Command
{
    protected $signature = 'food-items:expire';
    protected $description = 'Expire food items whose expiry date or available date (for daily) has passed.';

    public function handle()
    {
        $now = Carbon::today();
        $expired = 0;
        // Notify providers of soon-to-expire items (1 day before expiry)
        $soon = FoodItem::expiringSoon()->get();
        foreach ($soon as $item) {
            $provider = $item->provider;
            if ($provider) {
                $provider->notify(new FoodItemExpiringSoon($item));
            }
        }
        // Expire items
        $items = FoodItem::where('status', 'active')
            ->where(function($q) use ($now) {
                $q->where(function($q2) use ($now) {
                    $q2->where('order_type', 'daily')
                        ->where('available_date', '<', $now);
                })
                ->orWhere(function($q2) use ($now) {
                    $q2->whereIn('order_type', ['subscription', 'custom'])
                        ->whereNotNull('expiry_date')
                        ->where('expiry_date', '<', $now);
                });
            })->get();
        foreach ($items as $item) {
            $item->status = 'expired';
            $item->save();
            $expired++;
        }
        $this->info("Expired {$expired} food items.");
    }
} 