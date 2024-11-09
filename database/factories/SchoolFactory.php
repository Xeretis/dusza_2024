<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => $this->faker->company(),
            "street" => $this->faker->streetAddress(),
            "city" => $this->faker->city(),
            "state" => $this->faker->word(),
            "zip" => $this->faker->randomNumber(4),
            "contact_name" => $this->faker->name(),
            "contact_email" => fake()->unique()->safeEmail(),
        ];
    }
}
