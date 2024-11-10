<?php

namespace App\Filament\Teacher\Resources\TeamResource\Pages;

use App\Filament\Teacher\Resources\TeamResource;
use App\Models\CompetitorProfile;
use App\Settings\CompetitionSettings;
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
            Actions\CreateAction::make()->disabled(function () {
                $competitionSettings = app(CompetitionSettings::class);
                return !($competitionSettings->registration_deadline->isFuture() && $competitionSettings->registration_cancelled_at == null);
            }),
        ];
    }
}
