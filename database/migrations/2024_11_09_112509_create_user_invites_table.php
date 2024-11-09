<?php

use App\Enums\UserRole;
use App\Models\CompetitorProfile;
use App\Models\School;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("user_invites", function (Blueprint $table) {
            $table->id();
            $table
                ->enum("role", UserRole::values())
                ->default(UserRole::Competitor->value);
            $table->string("email")->unique();
            $table->string("token")->unique();
            $table->timestamp("expires_at")->nullable();
            $table->timestamp("accepted_at")->nullable();
            $table->foreignIdFor(CompetitorProfile::class, 'competitor_profile_id')->nullable()->constrained();
            $table
                ->foreignIdFor(School::class, "school_id")
                ->nullable()
                ->constrained();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("user_invites");
    }
};
