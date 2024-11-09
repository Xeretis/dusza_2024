<?php

namespace App\Filament\Organizer\Resources\UserResource\RelationManagers;

use App\Filament\Organizer\Resources\UserResource\Pages\ViewUser;
use Illuminate\Database\Eloquent\Model;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class AuditRelationManager extends AuditsRelationManager
{
    public static function canViewForRecord(
        Model $ownerRecord,
        string $pageClass
    ): bool {
        return $pageClass == ViewUser::class;
    }
}
