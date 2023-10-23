<?php

namespace App\Notifications;

use App\Filament\Resources\OrderDetailRefundResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderDetailRefundCreatedForAdminNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct() {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('New Refund Request requires your review.')
                    ->action('Go to refunds', OrderDetailRefundResource::getUrl());
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
