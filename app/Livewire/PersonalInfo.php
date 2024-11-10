<?php

namespace App\Livewire;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as BaseComponent;

class PersonalInfo extends BaseComponent
{
    public array $only = ['username', 'email'];

    public static function canView(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return 'my-breezy-personal-info';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    $this->getUsernameComponent(),
                    $this->getEmailComponent(),
                ])->columnSpan(2)
            ])->columns()
            ->statePath('data');
    }

    protected function getUsernameComponent()
    {
        return TextInput::make('username')
            ->label('Felhasználónév')
            ->required();
    }
}
