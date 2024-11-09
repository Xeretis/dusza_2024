<?php

use App\Jobs\ChunkedStreetImportJob;
use App\Jobs\StreetImportJob;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("streets", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("zip");
            $table->string("name");
        });

        ChunkedStreetImportJob::dispatch();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("streets");
    }
};
