<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EducationFactory extends Factory
{
    protected $model = Education::class;

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
}
