<?php

namespace App\Settings;

use App\Settings\Casts\CarbonCast;
use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;
use Spatie\LaravelSettings\SettingsCasts\DateTimeInterfaceCast;

class CompetitionSettings extends Settings
{
    public string $name;
    public ?string $description;
    public string $registration_deadline;
    public ?string $registration_cancelled_at;

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
