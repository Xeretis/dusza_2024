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
        Schema::create("team_competitor_profile", function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Team::class, "team_id");
            $table->foreignIdFor(
                \App\Models\CompetitorProfile::class,
                "competitor_profile_id"
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("team_competitor_profile");
    }
};
