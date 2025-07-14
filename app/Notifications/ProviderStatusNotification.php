<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProviderStatusNotification extends Notification
{
    use Queueable;

    public $status;
    public $customMessage;

    public function __construct($status, $customMessage = null)
    {
        $this->status = $status;
        $this->customMessage = $customMessage;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $statusText = $this->status === 'approved' ? 'approved' : 'rejected';
        $message = $this->status === 'approved'
            ? 'Congratulations! Your provider account has been approved. You can now log in and start listing your food items.'
            : 'We regret to inform you that your provider account has been rejected.';
        return (new MailMessage)
            ->subject('Your Provider Account Status')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line($message)
            ->when($this->customMessage, fn($mail) => $mail->line($this->customMessage))
            ->action('Login to Your Account', url('/login'))
            ->line('Thank you for your interest in joining Sarvana!');
    }

    public function toArray($notifiable)
    {
        return [
            'status' => $this->status,
            'message' => $this->status === 'approved'
                ? 'Your provider account has been approved.'
                : 'Your provider account has been rejected.',
        ];
    }
} 