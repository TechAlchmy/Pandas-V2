<?php

namespace App\Notifications;

use App\Models\OrganizationInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Action;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

class OrganizationInvitationCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected OrganizationInvitation $organizationInvitation,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('You have been invited to :organization', ['organization' => $this->organizationInvitation->organization->name]))
            ->line(__('You have been invited to join the :organization !', ['organization' => $this->organizationInvitation->organization->name]))
            ->when($this->organizationInvitation->is_manager, fn ($mail) => $mail->line('We can\'t wait for your contribution as a manager'))
            ->line( __('You may accept this invitation by clicking the button below:'))
            ->action('Accept Invitation', URL::signedRoute('organization-invitations.accept', ['record' => $this->organizationInvitation]))
            ->line(__('If you did not expect to receive an invitation to this organization, you may discard this email.'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
