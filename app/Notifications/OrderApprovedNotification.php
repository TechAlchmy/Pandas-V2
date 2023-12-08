<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class OrderApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected $order
    ) {}

    public function via(object $notifiable): array
    {
        $via = ['mail'];

        if ($notifiable->userPreference?->sms_notification) {
            $via[] = 'vonage';
        }

        return $via;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Thank you for your purchase!')
                    ->line('Order #' . $this->order->order_column)
                    ->line('Total $' . $this->order->order_total / 100)
                    ->action('View Orders', route('dashboard', ['activeTab' => 4]))
                    ->line('Thank you for using panda!');
    }

    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage())
            ->clientReference((string) $notifiable->uuid)
            ->content('Thank you for your purchase #'.$this->order->order_column);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
