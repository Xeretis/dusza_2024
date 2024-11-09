<?php

namespace App\Jobs;

use App\Models\Street;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class StreetImportJob implements ShouldQueue
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
        $streets = [];
        while (($data = fgetcsv($file, separator: ";")) !== false) {
            $streets[] = [
                "zip" => $data[0],
                "name" => $data[1],
            ];
        }

        Street::insert($streets);
        gc_collect_cycles();

        fclose($file);
        DB::connection()->enableQueryLog();
    }
}
