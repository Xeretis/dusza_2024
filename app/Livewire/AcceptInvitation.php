<?php

namespace App\Livewire;

use App\Models\CompetitorProfile;
use App\Models\School;
use App\Models\User;
use App\Models\UserInvite;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Actions\Action;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasTopbar;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\SimplePage;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Url;

class AcceptInvitation extends SimplePage
{
    protected static string $view = 'livewire.accept-invitation';

    use HasTopbar;
    use WithRateLimiting;
    use InteractsWithFormActions;
    use CanUseDatabaseTransactions;

    protected static ?string $title = 'Meghívó elfogadása';
    #[Url]
    public string $token;
    public ?UserInvite $userInvite;
    public array $data;
    protected ?string $maxWidth = MaxWidth::FourExtraLarge->value;

    public function getSubheading(): string|Htmlable|null
    {
        return $this->userInvite != null ? 'Egy felhasználó meghívott, hogy regisztrálj a Dusza verseny jelentkezési felületére.' : null;
    }

    public function mount()
    {
        if (empty($this->token)) {
            return;
        }

        $this->userInvite = UserInvite::where('token', $this->token)->whereNull('accepted_at')->where('expires_at', '>', now())->orWhereNull('expires_at')->first();

        if ($this->userInvite != null) {
            $this->data = [
                'email' => $this->userInvite->email,
                'role' => $this->userInvite->role->getLabel(),
                'school_id' => $this->userInvite->school_id != null ? School::find($this->userInvite->school_id)->name : 'Nem értelmezhető'
            ];

            $this->form->fill($this->data);
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('username')
                ->label('Felhasználónév')
                ->required()
                ->unique('users', 'username'),
            TextInput::make('password')
                ->label(__('filament-panels::pages/auth/register.form.password.label'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required()
                ->rule(Password::default())
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->same('passwordConfirmation')
                ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute')),
            TextInput::make('passwordConfirmation')
                ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                ->password()
                ->revealable(filament()->arePasswordsRevealable())
                ->required()
                ->dehydrated(false),
            TextInput::make('email')
                ->label('E-mail cím')
                ->disabled()
                ->dehydrated(false),
            TextInput::make('role')
                ->label('Szerepkör')
                ->disabled(),
            TextInput::make('school_id')
                ->label('Iskola')
                ->disabled()
        ])->columns(2)->statePath('data');
    }

    public function accept()
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $user = $this->wrapInDatabaseTransaction(function () {
            $data = $this->form->getState();

            $user = User::create(collect($data)->only(['username', 'password'])->merge([
                'email' => $this->userInvite->email,
                'role' => $this->userInvite->role,
                'school_id' => $this->userInvite->school_id,
                'email_verified_at' => now()
            ])->toArray());

            $this->userInvite->accepted_at = now();

            $this->userInvite->save();

            if ($this->userInvite->competitor_profile_id != null) {
                CompetitorProfile::find($this->userInvite->competitor_profile_id)->update([
                    'user_id' => $user->id
                ]);
            }

            return $user;
        });

        event(new Registered($user));

        Filament::auth()->login($user);

        session()->regenerate();

        return app(RegistrationResponse::class);
    }

    protected function getRateLimitedNotification(TooManyRequestsException $exception): ?Notification
    {
        return Notification::make()
            ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => $exception->minutesUntilAvailable,
            ]))
            ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => $exception->minutesUntilAvailable,
            ]) : null)
            ->danger();
    }

    public function hasLogo(): bool
    {
        return true;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getRegisterFormAction(),
        ];
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('accept')
            ->label('Meghívó elfogadása')
            ->submit('accept');
    }

    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxWidth' => $this->getMaxWidth(),
        ];
    }
}
