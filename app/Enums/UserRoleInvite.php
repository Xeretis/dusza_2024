<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRoleInvite: string implements HasLabel, HasColor
{
    use ValueTrait;

    case Organizer = 'organizer';
    case SchoolManager = 'school-manager';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Organizer => 'Szervező',
            self::SchoolManager => 'Iskola menedzser',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Organizer => 'danger',
            self::SchoolManager => 'warning',
        };
    }
}
