<?php

namespace App\Filament\Organizer\Resources\CategoryResource\Pages;

use App\Filament\Imports\CategoryImporter;
use App\Filament\Organizer\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->label('Kategóriák importálása')
                ->importer(CategoryImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
