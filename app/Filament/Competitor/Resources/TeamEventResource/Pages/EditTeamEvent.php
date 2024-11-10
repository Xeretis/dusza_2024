<?php

namespace App\Filament\Competitor\Resources\TeamEventResource\Pages;

use App\Filament\Competitor\Resources\TeamEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeamEvent extends EditRecord
{
    protected static string $resource = TeamEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
