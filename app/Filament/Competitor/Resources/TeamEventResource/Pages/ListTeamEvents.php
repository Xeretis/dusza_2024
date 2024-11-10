<?php

namespace App\Filament\Competitor\Resources\TeamEventResource\Pages;

use App\Filament\Competitor\Resources\TeamEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeamEvents extends ListRecords
{
    protected static string $resource = TeamEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
