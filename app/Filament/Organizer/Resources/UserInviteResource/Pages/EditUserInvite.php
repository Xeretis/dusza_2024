<?php

namespace App\Filament\Organizer\Resources\UserInviteResource\Pages;

use App\Filament\Organizer\Resources\UserInviteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserInvite extends EditRecord
{
    protected static string $resource = UserInviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
