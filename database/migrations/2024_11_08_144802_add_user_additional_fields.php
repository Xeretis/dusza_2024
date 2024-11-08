<?php

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
        Schema::table("users", function (Blueprint $table) {
            $table->string("username");
            $table->tinyInteger("role")->default(0);
            $table
                ->foreignIdFor(School::class, "school_id")
                ->nullable()
                ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("users", function (Blueprint $table) {
            $table->dropColumn("username");
            $table->dropColumn("role");
            $table->dropForeign(["school_id"]);
            $table->dropColumn("school_id");
        });
    }
};
