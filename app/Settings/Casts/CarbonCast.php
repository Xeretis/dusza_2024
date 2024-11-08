<?php

namespace App\Settings\Casts;

use Carbon\Carbon;
use Spatie\LaravelSettings\SettingsCasts\SettingsCast;

class CarbonCast implements SettingsCast
{
    public function get($payload): ?Carbon
    {
        if ($payload == null)
            return null;

        return Carbon::parse($payload);
    }

    public function set($payload)
    {
        if ($payload == null)
            return null;

        if ($payload instanceof Carbon)
            return $payload->toString();

        return Carbon::parse($payload)->toString();
    }
}
