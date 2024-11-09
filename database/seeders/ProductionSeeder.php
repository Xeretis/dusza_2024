<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::whereEmail("admin@example.com")->exists()) {
            return;
        }

        $adminUser = new User([
            "name" => "Administrator (Built-in)",
            "email" => "admin@example.com",
            "password" => Hash::make("password"),
            "role" => UserRole::Organizer->value,
            "email_verified_at" => now(),
            "remember_token" => Str::random(10),
            "username" => "admin",
        ]);

        $adminUser->save();
    }
}
