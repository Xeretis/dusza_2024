<?php

namespace App\Filament\Organizer\Resources\ProgrammingLanguageResource\Pages;

use App\Filament\Organizer\Resources\ProgrammingLanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProgrammingLanguages extends ListRecords
{
    protected static string $resource = ProgrammingLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
