<?php

namespace App\Overrides;

class PhpBinary
{
    /**
     * Get the path to the PHP executable.
     *
     * @return string
     */
    public static function path()
    {
        $escape = '\\' === DIRECTORY_SEPARATOR ? '"' : '\'';

        if (config('app.horizon_binary_override')) {
            return $escape . config('app.horizon_binary_override') . $escape;
        }

        return $escape . PHP_BINARY . $escape;
    }
}
