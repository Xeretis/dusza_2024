<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum TeamStatus: string implements HasLabel, HasColor
{
    use ValueTrait;
    case Inactive = "inactive";
    case SchoolApproved = "school_approved";
    case OrganizerApproved = "organizer_approved";

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Inactive => "Inaktív",
            self::SchoolApproved => "Iskola jóváhagyta",
            self::OrganizerApproved => "Szervező jóváhagyta",
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Inactive => "danger",
            self::SchoolApproved => "warning",
            self::OrganizerApproved => "success",
        };
    }
}
