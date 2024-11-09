<?php

namespace App\Livewire;

use App\Models\UserInvite;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Concerns\HasMaxWidth;
use Filament\Pages\Concerns\HasTopbar;
use Filament\Pages\SimplePage;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\Url;

class AcceptInvitation extends SimplePage
{
    protected static string $view = 'livewire.accept-invitation';

    use HasMaxWidth;
    use HasTopbar;

    protected static ?string $title = 'Meghívó elfogadása';
    #[Url]
    public string $token;
    public ?UserInvite $userInvite = null;

    public function getSubheading(): string|Htmlable|null
    {
        return $this->userInvite != null ? 'Egy felhasználó meghívott, hogy regisztrálj a Dusza verseny jelentkezési felületére.' : null;
    }

    public function mount()
    {
        if (empty($this->token)) {
            return;
        }


        $this->userInvite = UserInvite::where('token', $this->token)->first();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('username')
                ->label('Felhasználónév')
                ->required()
                ->unique('users', 'username')
        ]);
    }

    public function hasLogo(): bool
    {
        return true;
    }

    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxWidth' => $this->getMaxWidth(),
        ];
    }
}
