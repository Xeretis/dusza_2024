<?php

namespace App\Overrides;

use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\PhpExecutableFinder as SymphonyPhpExecutableFinder;

class PhpExecutableFinder extends SymphonyPhpExecutableFinder
{
    /**
     * Finds The PHP executable.
     */
    #[\Override]
    public function find(bool $includeArgs = true): string|false {
        if (config('app.horizon_binary_override', false)) {
            return config('app.horizon_binary_override');
        }

        if ($herdPath = getenv('HERD_HOME')) {
            return (new ExecutableFinder())->find('php', false, [
                implode(DIRECTORY_SEPARATOR, [$herdPath, 'bin']),
            ]);
        }

        return parent::find($includeArgs);
    }
}
