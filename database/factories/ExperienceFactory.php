<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'started_at' => Carbon::now(),
            'ended_at' => Carbon::now(),
            'description' => $this->faker->text(),
            'location' => $this->faker->word(),
        ];
    }

    public function current(): ExperienceFactory
    {
        return $this->state(fn() => [
            'ended_at' => null,
        ]);
    }
}
