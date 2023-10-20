<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected $order
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Thank you for your purchase!')
                    ->line('Order #' . $this->order->order_column)
                    ->line('Total $' . $this->order->order_total)
                    ->action('View Orders', route('dashboard', ['activeTab' => 4]))
                    ->line('Thank you for using panda!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
