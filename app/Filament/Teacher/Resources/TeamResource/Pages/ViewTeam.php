<?php

namespace App\Filament\Teacher\Resources\TeamResource\Pages;

use App\Filament\Teacher\Resources\TeamResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTeam extends ViewRecord
{
    protected static string $resource = TeamResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        if (auth()->guest())
            return false;

        return auth()->user()->teams()
            ->where('teams.id', $parameters['record']->id)
            ->exists();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
