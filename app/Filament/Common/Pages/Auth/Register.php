<?php

namespace App\Filament\Common\Pages\Auth;


use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class Register extends \Filament\Pages\Auth\Register
{
    public function form(Form $form): Form
    {
        return $form->schema([
            $this->getUsernameFormComponent(),
            $this->getEmailFormComponent(),
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
}
