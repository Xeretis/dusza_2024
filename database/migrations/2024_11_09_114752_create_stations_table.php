<?php

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
        });

        $file = fopen("resources/store/telepules_lista.csv", "r");
        $data = fgetcsv($file);
        while (($data = fgetcsv($file, separator: ";")) !== false) {
            DB::table("stations")->insert([
                "zip" => $data[0],
                "city" => $data[2],
                "state" => $data[1],
            ]);
        }
        fclose($file);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("stations");
    }
};
