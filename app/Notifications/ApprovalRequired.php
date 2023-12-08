<?php

namespace App\Notifications;

use App\Models\Discount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApprovalRequired extends Notification
{
    use Queueable;

    protected $count;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->count = Discount::where('is_approved', false)->count();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $arr = [];
        if ($this->count) {
            $arr[] = 'mail';
        }
        return $arr;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('There are ' . $this->count . ' discount(s) waiting for approval.')
            ->action('Approve Now', url('/admin/discounts?&tableFilters[is_approved][value]=1'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
