<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Tests\Integration\Database\EloquentHasManyThroughTest\Category;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            "email" => "test@example.com",
        ]);

        User::factory()->create([
            "email" => "test2@example.com",
            "role" => UserRole::SchoolManager,
        ]);
        Category::factory()->create([
            "name" => "Test category",
        ]);
    }
}
