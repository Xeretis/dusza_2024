<?php

namespace App\Filament\Organizer\Resources\SchoolResource\RelationManagers;

use App\Filament\Organizer\Resources\SchoolResource\Pages\ViewSchool;
use Illuminate\Database\Eloquent\Model;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class AuditRelationManager extends AuditsRelationManager
{
    public static function canViewForRecord(
        Model $ownerRecord,
        string $pageClass
    ): bool {
        return $pageClass == ViewSchool::class;
    }
}
