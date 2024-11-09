<?php

use App\Models\Team;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table("competitor_profiles", function (Blueprint $table) {
            $table->dropColumn("team_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("competitor_profiles", function (Blueprint $table) {
            $table->foreignIdFor(Team::class, "team_id");
        });
    }
};