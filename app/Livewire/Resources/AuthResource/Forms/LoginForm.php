<?php

namespace App\Livewire\Resources\AuthResource\Forms;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class LoginForm extends Component implements HasForms
{
    use InteractsWithForms;
    use WithRateLimiting;

    public $data;

    public function mount()
    {
        $this->form->fill();
    }

    public function authenticate()
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        if (! auth()->attempt(Arr::only($data, ['email', 'password']), $data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'data.email' => __('filament-panels::pages/auth/login.messages.failed'),
            ]);
        }

        session()->regenerate();

        return redirect()->intended();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->hiddenLabel()
                    ->placeholder('Email')
                    ->extraInputAttributes(['class' => 'placeholder-black'], true)
                    ->view('forms.components.text-input')
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->hiddenLabel()
                    ->placeholder('Password')
                    ->extraInputAttributes(['class' => 'placeholder-black'])
                    ->view('forms.components.text-input')
                    ->password()
                    ->required(),
                Forms\Components\Checkbox::make('remember_me')
                    ->hint(fn () => new HtmlString(Blade::render('
                        <x-a class="inline-block underline-animated text-sm text-gray-600" href="/forgot-password">
                            {{ __("Forgot your password?") }}
                        </x-a>
                    ')))
                    ->default(false),
            ]);
    }
}
