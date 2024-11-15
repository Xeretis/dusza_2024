<?php

namespace App\Filament\Organizer\Resources\ProgrammingLanguageResource\Pages;

use App\Filament\Organizer\Resources\ProgrammingLanguageResource;
use App\Models\ProgrammingLanguage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgrammingLanguage extends EditRecord
{
    protected static string $resource = ProgrammingLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->disabled(
                fn(ProgrammingLanguage $record) => $record
                    ->teams()
                    ->exists()
            ),
        ];
    }
}
