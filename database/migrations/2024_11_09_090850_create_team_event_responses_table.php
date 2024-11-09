<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('team_event_responses', function (Blueprint $table) {
            $table->id();
            $table->string('message')->nullable();
            $table->json('changes');
            $table->enum('status', ["pending", "approved", "rejected"])->default('pending');
            $table->foreignId('team_event_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_event_responses');
    }
};
