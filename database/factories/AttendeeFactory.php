<?php

namespace Database\Factories;

use App\Models\Attendee;
use App\Models\Conference;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttendeeFactory extends Factory
{
    protected $model = Attendee::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'ticket_cost' => $this->faker->randomNumber(),
            'is_paid' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => Carbon::now(),
            'conference_id' => Conference::factory(),
        ];
    }
}
