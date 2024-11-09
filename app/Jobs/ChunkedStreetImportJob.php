<?php

namespace App\Jobs;

use App\Models\Street;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ChunkedStreetImportJob implements ShouldQueue
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
        Street::truncate();
        $path = base_path("resources/store/streets");
        $files = scandir($path);

        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if ($file === "." || $file === "..") {
                continue;
            }

            dispatch(new StreetImportJob($path . "/" . $file));
        }
    }
}
