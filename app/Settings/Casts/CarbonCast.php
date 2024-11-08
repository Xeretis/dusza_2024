<?php

namespace App\Settings\Casts;

use Carbon\Carbon;
use Spatie\LaravelSettings\SettingsCasts\SettingsCast;

class CarbonCast implements SettingsCast
{

    public function get($payload)
    {
        return Carbon::parse($payload);
    }

    public function set($payload)
    {
        if ($payload instanceof Carbon)
            return $payload->toString();

        return Carbon::parse($payload)->toString();
    }
}
