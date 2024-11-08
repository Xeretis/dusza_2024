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
        Schema::create("team_events", function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("team_id");
            $table->enum("type", ["approval", "amend_request"]);
            $table->enum("status", ["pending", "approved", "rejected"]);
            $table->enum("scope", ["organizer", "school"]);
            $table->string("artifact_url");
            $table->string("message");
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
