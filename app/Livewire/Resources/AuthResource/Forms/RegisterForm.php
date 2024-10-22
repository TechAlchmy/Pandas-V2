<?php

namespace App\Livewire\Resources\AuthResource\Forms;

use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class RegisterForm extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public $data;

    public function mount()
    {
        $this->form->fill();
    }

    public function register()
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $user = User::query()->create($data);

        $user->userPreference()->create();

        event(new Registered($user));

        auth()->login($user);

        session()->regenerate();

        return redirect()->intended();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->hiddenLabel()
                    ->placeholder('Name')
                    ->maxLength(255)
                    ->autofocus()
                    ->required()
                    ->extraInputAttributes(['class' => 'placeholder-black'])
                    ->view('forms.components.text-input'),
                Forms\Components\TextInput::make('email')
                    ->hiddenLabel()
                    ->placeholder('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(User::class)
                    ->extraInputAttributes(['class' => 'placeholder-black'])
                    ->view('forms.components.text-input'),
                Forms\Components\TextInput::make('password')
                    ->hiddenLabel()
                    ->placeholder('Password')
                    ->password()
                    ->view('forms.components.text-input')
                    ->rule(Password::default())
                    ->same('passwordConfirmation')
                    ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute'))
                    ->required(),
                Forms\Components\TextInput::make('passwordConfirmation')
                    ->hiddenLabel()
                    ->placeholder('Password Confirmation')
                    ->view('forms.components.text-input')
                    ->dehydrated(false)
                    ->required()
                    ->password(),
            ]);
    }
}
