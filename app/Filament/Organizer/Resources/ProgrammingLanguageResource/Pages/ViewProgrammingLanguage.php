<?php

namespace App\Filament\Organizer\Resources\ProgrammingLanguageResource\Pages;

use App\Filament\Organizer\Resources\ProgrammingLanguageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProgrammingLanguage extends ViewRecord
{
    protected static string $resource = ProgrammingLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
