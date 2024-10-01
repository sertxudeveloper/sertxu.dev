<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

final class ExperienceFactory extends Factory
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

    public function current(): self
    {
        return $this->state(fn (): array => [
            'ended_at' => null,
        ]);
    }
}
