<?php

namespace App\Jobs;

use App\Models\Station;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ChunkedStationImportJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Station::truncate();
        $path = base_path("resources/store/stations");
        $files = scandir($path);

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if ($file === "." || $file === "..") {
                continue;
            }

            dispatch(new StationImportJob($path . "/" . $file));
        }
    }
}
