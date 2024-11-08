<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("competitor_profiles", function (Blueprint $table) {
            $table->id();
            $table->string("email")->nullable();
            $table->string("name");
            $table->integer("grade")->nullable();
            $table->foreignIdFor(Team::class, "team_id")->constrained();
            $table
                ->foreignIdFor(User::class, "user_id")
                ->nullable()
                ->constrained();
            $table->tinyInteger("type")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("competitor_profiles");
    }
};
