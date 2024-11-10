<?php

namespace App\Filament\Common\Pages\Auth;


use App\Enums\UserRole;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class Register extends \Filament\Pages\Auth\Register
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getUsernameFormComponent(),
            $this->getEmailFormComponent(),
            $this->getRoleFormComponent(),
            $this->getPasswordFormComponent(),
            $this->getPasswordConfirmationFormComponent(),
        ])->statePath('data');
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label('Felhasználónév')
            ->required()
            ->maxLength(255)
            ->unique('users', 'username')
            ->autofocus();
    }

    protected function getRoleFormComponent(): Component
    {
        return Select::make('role')
            ->label('Szerepkör')
            ->options([
                UserRole::Competitor->value => UserRole::Competitor->getLabel(),
                UserRole::Teacher->value => UserRole::Teacher->getLabel()
            ])
            ->hintIcon('heroicon-m-information-circle')
            ->hintIconTooltip('Ha nem versenyzőként vagy tanárként szeretnél regisztrálni, vedd fel a kapcsolatot a szervezőkkel, hogy meghívhassanak!')
            ->native(false)
            ->selectablePlaceholder(false)
            ->required()
            ->in([UserRole::Competitor->value, UserRole::Teacher->value]);
    }
}
