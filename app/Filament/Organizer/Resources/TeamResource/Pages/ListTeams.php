<?php

namespace App\Filament\Organizer\Resources\TeamResource\Pages;

use App\Filament\Exports\TeamExporter;
use App\Filament\Organizer\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->exporter(TeamExporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
