<?php

namespace Database\Factories;

use App\Models\Talk;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Speaker;

class SpeakerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Speaker::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'bio' => $this->faker->text(),
            'twitter_handle' => $this->faker->word(),
            'qualifications' => $this->faker->randomElements(['PhD', 'MSc', 'BSc'], $this->faker->numberBetween(1, 3)),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }

    public function withTalks($count = 1): self
    {
        return $this->has(Talk::factory()->count($count), 'talks');
    }
}
