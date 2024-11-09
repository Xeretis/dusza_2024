<?php

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
        Schema::create("team_events", function (Blueprint $table) {
            $table->id();
            $table->string("message");
            $table->foreignId("team_id")->constrained()->onDelete('cascade');
            $table->enum("type", ["approval", "amend_request"]);
            $table->enum("status", ["pending", "approved", "rejected"]);
            $table->enum("scope", ["organizer", "school"]);
            $table->foreignIdFor(User::class, 'user_id');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("team_events");
    }
};
