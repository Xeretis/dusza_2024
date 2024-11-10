<?php

namespace App\Filament\Teacher\Resources\TeamResource\Pages;

use App\Filament\Teacher\Resources\TeamResource;
use App\Models\CompetitorProfile;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTeams extends ListRecords
{
    protected static string $resource = TeamResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return CompetitorProfile::where('user_id', auth()->id())->exists();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
