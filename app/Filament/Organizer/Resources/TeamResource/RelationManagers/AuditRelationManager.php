<?php

namespace App\Filament\Organizer\Resources\TeamResource\RelationManagers;

use App\Filament\Organizer\Resources\TeamResource\Pages\ViewTeam;
use Illuminate\Database\Eloquent\Model;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class AuditRelationManager extends AuditsRelationManager
{
    public static function canViewForRecord(
        Model $ownerRecord,
        string $pageClass
    ): bool {
        return $pageClass == ViewTeam::class;
    }
}
