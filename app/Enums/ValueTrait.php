<?php

namespace App\Enums;

trait ValueTrait
{
    public static function values(): array
    {
        return array_column(static::cases(), "value");
    }
}
