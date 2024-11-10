<?php

namespace App\Filament\Organizer\Resources\UserInviteResource\Pages;

use App\Filament\Organizer\Resources\UserInviteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserInvites extends ListRecords
{
    protected static string $resource = UserInviteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
