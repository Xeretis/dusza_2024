<?php

use App\Jobs\ChunkedStationImportJob;
use App\Jobs\StationImportJob;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("stations", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("zip");
            $table->string("city");
            $table->string("state");
            $table->index("zip");
            $table->index("city");
        });

        ChunkedStationImportJob::dispatch();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("stations");
    }
};
