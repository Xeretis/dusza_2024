<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel, HasColor
{
    case Competitor = "competitor";
    case Organizer = "organizer";
    case SchoolManager = "school-manager";
    case Teacher = "teacher";

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Competitor => 'Versenyző',
            self::Organizer => 'Szervező',
            self::SchoolManager => 'Iskola menedzser',
            self::Teacher => 'Tanár',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Competitor => 'primary',
            self::Organizer => 'danger',
            self::SchoolManager => 'warning',
            self::Teacher => 'info',
        };
    }
}
