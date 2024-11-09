<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CompetitorProfileType: int implements HasLabel, HasColor
{
    case Teacher = 0;
    case Student = 1;
    case SubstituteStudent = 2;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Teacher => 'Felkészítő tanár',
            self::Student => 'Csapattag',
            self::SubstituteStudent => 'Póttag',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Teacher => 'danger',
            self::Student => 'success',
            self::SubstituteStudent => 'warning',
        };
    }
}
