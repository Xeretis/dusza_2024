<?php

namespace App\Filament\Organizer\Resources\UserInviteResource\Pages;

use App\Filament\Organizer\Resources\UserInviteResource;
use App\Notifications\UserInviteNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CreateUserInvite extends CreateRecord
{
    protected static string $resource = UserInviteResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $model = static::getModel()::create([
            ...$data,
            'token' => Str::random(64),
        ]);

        Notification::route('mail', $model->email)->notify(
            new UserInviteNotification($model->token)
        );

        return $model;
    }
}
