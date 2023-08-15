<?php

namespace App\Livewire\Resources\AuthResource\Forms;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ResetPasswordForm extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public $password;

    public $passwordConfirmation;

    public $email;

    #[Locked]
    public $token;

    public function mount()
    {
        $this->token = request()->route('token');

        $this->form->fill();
    }

    public function resetPassword()
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $data['token'] = $this->token;

        $status = Password::reset(
            $data,
            function (CanResetPassword | Model | Authenticatable $user) use ($data) {
                $user->forceFill([
                    'password' => $data['password'],
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            },
        );

        if ($status === Password::PASSWORD_RESET) {
            Notification::make()
                ->title(__($status))
                ->success()
                ->send();

            return redirect()->intended();
        }

        Notification::make()
            ->title(__($status))
            ->danger()
            ->send();

        return null;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->hiddenLabel()
                    ->view('forms.components.text-input')
                    ->placeholder('Email')
                    ->autofocus(),
                Forms\Components\TextInput::make('password')
                    ->hiddenLabel()
                    ->view('forms.components.text-input')
                    ->placeholder(__('filament-panels::pages/auth/password-reset/reset-password.form.password.label'))
                    ->password()
                    ->required()
                    ->rule(RulesPassword::default())
                    ->same('passwordConfirmation')
                    ->validationAttribute(__('filament-panels::pages/auth/password-reset/reset-password.form.password.validation_attribute')),
                Forms\Components\TextInput::make('passwordConfirmation')
                    ->hiddenLabel()
                    ->view('forms.components.text-input')
                    ->placeholder(__('filament-panels::pages/auth/password-reset/reset-password.form.password_confirmation.label'))
                    ->password()
                    ->required()
                    ->dehydrated(false)
            ]);
    }
}
