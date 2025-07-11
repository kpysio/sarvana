<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\FoodItem;

class FoodItemExpiringSoon extends Notification implements ShouldQueue
{
    use Queueable;

    public $foodItem;

    public function __construct(FoodItem $foodItem)
    {
        $this->foodItem = $foodItem;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your food item is expiring soon!')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your food item "' . $this->foodItem->title . '" will expire soon (on ' . ($this->foodItem->expiry_date ?? $this->foodItem->available_date) . ').')
            ->action('View Item', url(route('food-items.show', $this->foodItem->id)))
            ->line('Please update or extend the expiry if you wish to keep it active.');
    }

    public function toArray($notifiable)
    {
        return [
            'food_item_id' => $this->foodItem->id,
            'title' => $this->foodItem->title,
            'expiry_date' => $this->foodItem->expiry_date ?? $this->foodItem->available_date,
            'message' => 'Your food item "' . $this->foodItem->title . '" will expire soon.'
        ];
    }
} 