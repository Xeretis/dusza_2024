<?php

namespace App\Filament\Organizer\Resources\CategoryResource\Pages;

use App\Filament\Organizer\Resources\CategoryResource;
use App\Models\Category;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->disabled(
                fn(Category $record) => $record->teams()->exists()
            ),
        ];
    }
}
