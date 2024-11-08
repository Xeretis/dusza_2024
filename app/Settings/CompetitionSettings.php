<?php

namespace App\Settings;

use App\Settings\Casts\CarbonCast;
use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

class CompetitionSettings extends Settings
{
    public string $name;
    public ?string $description;
    public Carbon|string $registration_deadline;
    public Carbon|string|null $registration_cancelled_at;

    public static function group(): string
    {
        return 'competition';
    }

    public static function casts(): array
    {
        return [
            'registration_deadline' => CarbonCast::class,
            'registration_cancelled_at' => CarbonCast::class
        ];
    }
}
