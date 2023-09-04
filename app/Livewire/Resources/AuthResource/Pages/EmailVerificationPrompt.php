<?php

namespace App\Livewire\Resources\AuthResource\Pages;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Notifications\Notification;
use Livewire\Component;

class EmailVerificationPrompt extends Component
{
    use WithRateLimiting;

    public function mount()
    {
        if (auth()->user()->hasVerifiedEmail() && auth()->user()->organization_verified_at) {
            abort(redirect()->route('dashboard'));
        }
    }

    public function resend()
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/email-verification/email-verification-prompt.notifications.notification_resend_throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/email-verification/email-verification-prompt.notifications.notification_resend_throttled') ?: []) ? __('filament-panels::pages/auth/email-verification/email-verification-prompt.notifications.notification_resend_throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return;
        }

        $user = auth()->user();

        if (! method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new \Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        $user->sendEmailVerificationNotification();

        Notification::make()
            ->title(__('filament-panels::pages/auth/email-verification/email-verification-prompt.notifications.notification_resent.title'))
            ->success()
            ->send();
    }
}
