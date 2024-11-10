<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('competitor_profiles', function (Blueprint $table) {
            $table->json('school_ids')->after('type')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('competitor_profiles', function (Blueprint $table) {
            $table->dropColumn('school_ids');
        });
    }
};
