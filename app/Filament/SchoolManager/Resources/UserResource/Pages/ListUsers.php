<?php

namespace App\Filament\SchoolManager\Resources\UserResource\Pages;

use App\Enums\UserRole;
use App\Filament\SchoolManager\Resources\UserResource;
use App\Models\UserInvite;
use App\Notifications\UserInviteNotification;
use Filament\Actions;
use Filament\Notifications\Notification as NotificationsNotification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Notification;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('invite')
                ->label('Felhasználó meghívása')
                ->modalDescription(
                    'Kérlek add meg az email címét, amelyre meghívót szeretnél küldeni.'
                )
                ->form([
                    \Filament\Forms\Components\TextInput::make('email')
                        ->label('Email cím')
                        ->required()
                        ->email(),
                ])
                ->action(function (array $data) {
                    $email = $data['email'];

                    $invite = UserInvite::create([
                        'email' => $email,
                        'token' => \Illuminate\Support\Str::random(64),
                        'school_id' => auth()->user()->school_id,
                        'role' => UserRole::SchoolManager,
                    ]);

                    Notification::route('mail', $invite->email)->notify(
                        new UserInviteNotification($invite->token)
                    );

                    NotificationsNotification::make()
                        ->title('Felhasználó meghívva')
                        ->body('A felhasználót sikeresen meghívtad.')
                        ->success()
                        ->send();
                }),
        ];
    }
}
