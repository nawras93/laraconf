<?php

namespace Database\Factories;

use App\Enums\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Conference;
use App\Models\Venue;

class ConferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Conference::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement([
                'draft' => 'Draft',
                'published' => 'Published',
                'cancelled' => 'Cancelled',
            ]),
            'region' => $this->faker->randomElement(Region::class),
            'venue_id' => null, // in case you want to use the VenueFactory
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
