<?php

use App\Models\Category;
use App\Models\ProgrammingLanguage;
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
        Schema::create("teams", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->foreignIdFor(Category::class, "category_id")->constrained();
            $table
                ->foreignIdFor(
                    ProgrammingLanguage::class,
                    "programming_language_id"
                )
                ->constrained();
            $table->foreignIdFor(School::class, "school_id")->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("teams");
    }
};
