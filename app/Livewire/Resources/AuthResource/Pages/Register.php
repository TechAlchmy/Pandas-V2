<?php

namespace App\Livewire\Resources\AuthResource\Pages;

use App\Models\Organization;
use App\Models\User;
use App\Notifications\SendUserUnderVerificationNotification;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Register extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public $data;

    #[Locked]
    public $organizationUuid;

    public function mount()
    {
        $this->form->fill();
        $this->organizationUuid = request('organization_uuid');
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

        $data['organization_id'] = $this->organization?->getKey()
            ?? Organization::query()
                ->where('user_registration_code', \data_get($data, 'user_registration_code'))
                ->value('id');

        \data_forget($data, 'user_registration_code');

        $user = User::query()->create($data);

        $user->userPreference()->create();

        event(new Registered($user));

        auth()->login($user);

        $user->notify(new SendUserUnderVerificationNotification);

        session()->regenerate();

        return redirect()->intended();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('user_registration_code')
                    ->hiddenLabel()
                    ->placeholder('Registration Code')
                    ->maxLength(255)
                    ->autofocus()
                    ->visible(fn ($livewire) => empty($livewire->organizationUuid))
                    ->required(fn ($livewire) => empty($livewire->organizationUuid))
                    ->dehydrateStateUsing(fn ($state) => \strtoupper($state))
                    ->extraAlpineAttributes(['x-on:keyup' => '$el.value = $el.value.toUpperCase();'])
                    ->view('forms.components.text-input')
                    ->exists(Organization::class)
                    ->validationAttribute('Registration Code'),
                Forms\Components\TextInput::make('name')
                    ->hiddenLabel()
                    ->placeholder('Name')
                    ->maxLength(255)
                    ->autofocus()
                    ->required()
                    ->view('forms.components.text-input'),
                Forms\Components\TextInput::make('email')
                    ->hiddenLabel()
                    ->placeholder('Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(User::class)
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

    public function render()
    {
        return view('livewire.resources.auth-resource.pages.register')
            ->layout('components.layouts.guest');
    }

    #[Computed]
    public function organization()
    {
        return Organization::query()
            ->firstWhere('uuid', $this->organizationUuid);
    }
}
