<?php

namespace App\Filament\Organizer\Resources\SchoolResource\Pages;

use App\Filament\Organizer\Resources\SchoolResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchool extends EditRecord
{
    protected static string $resource = SchoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
