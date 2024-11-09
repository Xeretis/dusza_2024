<?php

namespace App\Filament\Organizer\Resources\CategoryResource\RelationManagers;

use App\Filament\Organizer\Resources\CategoryResource\Pages\ViewCategory;
use Illuminate\Database\Eloquent\Model;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class AuditRelationManager extends AuditsRelationManager
{
    public static function canViewForRecord(
        Model $ownerRecord,
        string $pageClass
    ): bool {
        return $pageClass == ViewCategory::class;
    }
}
