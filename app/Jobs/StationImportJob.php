<?php

namespace App\Jobs;

use App\Models\Station;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StationImportJob implements ShouldQueue
{
    use Queueable;

    private string $path = "";

    /**
     * Create a new job instance.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // disable query log
        DB::connection()->disableQueryLog();

        $file = fopen($this->path, "r");
        $data = fgetcsv($file);
        $stations = [];
        while (($data = fgetcsv($file, separator: ";")) !== false) {
            $stations[] = [
                "zip" => $data[0],
                "city" => $data[2],
                "state" => $data[1],
            ];
        }

        Station::insert($stations);
        gc_collect_cycles();

        fclose($file);
        DB::connection()->enableQueryLog();
    }
}
