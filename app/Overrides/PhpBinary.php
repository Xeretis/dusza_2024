<?php

namespace App\Overrides;

use Laravel\Horizon\PhpBinary as HorizonPhpBinary;

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

        return $escape .
            config('app.horizon_binary_override', PHP_BINARY) .
            $escape;
    }
}
