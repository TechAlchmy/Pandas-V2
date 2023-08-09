<?php

namespace App\Livewire\Resources\OrganizationInvitationResource\Pages;

use App\Models\OrganizationInvitation;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AcceptInvitation extends Component implements HasForms, HasActions
{
    use InteractsWithFormActions;
    use InteractsWithActions;
    use InteractsWithForms;
    use WithRateLimiting;

    public $record;

    public $data;

    public function mount(int | string $record)
    {
        $this->record = $record;
        $this->form->fill([
            'email' => $this->getRecord()->email,
        ]);
    }

    #[Computed()]
    public function getRecord()
    {
        return OrganizationInvitation::query()->findOrFail($this->record);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->autofocus()
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->disabled()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label(__('filament-panels::pages/auth/register.form.password.label'))
                    ->password()
                    ->required()
                    ->rule(Password::default())
                    ->same('passwordConfirmation')
                    ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute')),
                Forms\Components\TextInput::make('passwordConfirmation')
                    ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                    ->password()
                    ->required()
                    ->dehydrated(false),
            ]);
    }

    public function register()
    {
        $data = $this->form->getState();

        $data['email'] = $this->getRecord()->email;
        $data['organization_id'] = $this->getRecord()->organization_id;
        $data['email_verified_at'] = now();

        $user = User::query()->create($data);

        if ($this->getRecord()->is_manager) {
            $user->managers()->create([
                'organization_id' => $this->getRecord()->organization_id,
            ]);
        }

        auth()->login($user);

        event(new Registered($user));

        $this->getRecord()->delete();

        return redirect()->route('dashboard');
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
            ->submit('register');
    }

    public function hasLogo(): bool
    {
        return false;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }

    public function getHeading(): string | Htmlable
    {
        return "You're invited to " . $this->getRecord()->organization->name;
    }

    public function getFormActionsAlignment(): string
    {
        return 'start';
    }

    public function areFormActionsSticky(): bool
    {
        return false;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getRegisterFormAction(),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
