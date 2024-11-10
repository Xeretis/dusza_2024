<?php

namespace App\Filament\SchoolManager\Resources\TeamResource\Pages;

use App\Enums\TeamStatus;
use App\Filament\SchoolManager\Resources\TeamResource;
use App\Models\Team;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Saade\FilamentAutograph\Forms\Components\SignaturePad;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
                // Actions\CreateAction::make(),
            ];
    }
}
