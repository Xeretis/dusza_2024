<?php

namespace App\Filament\Organizer\Resources\ProgrammingLanguageResource\RelationManagers;

use App\Filament\Organizer\Resources\ProgrammingLanguageResource\Pages\ViewProgrammingLanguage;
use Illuminate\Database\Eloquent\Model;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class AuditRelationManager extends AuditsRelationManager
{
    public static function canViewForRecord(
        Model $ownerRecord,
        string $pageClass
    ): bool {
        return $pageClass == ViewProgrammingLanguage::class;
    }
}
