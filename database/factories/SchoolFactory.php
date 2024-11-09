<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Station;
use App\Models\Street;
use Illuminate\Support\Facades\Log;
use function Amp\Future\await;

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
        sleep(10);

        $station = Station::find($this->faker->numberBetween(1, Station::count()));

        if (!$station) {
            Log::warning("No station found.");
            return [];
        }

        Log::info("Station: " . $station->zip);
        $street_id = Street::where("zip", $station->zip)->select("id")->get()->random();
        $street = Street::find($street_id);

        if (!$street) {
            Log::warning("No street found for zip: " . $station->zip);
            return [];
        }

        return [
            "name" => $this->faker->company(),
            "street" => $street->name,
            "city" => $station->city,
            "state" => $station->state,
            "zip" => $station->zip,
            "contact_name" => $this->faker->name(),
            "contact_email" => fake()->unique()->safeEmail(),
        ];
    }
}
