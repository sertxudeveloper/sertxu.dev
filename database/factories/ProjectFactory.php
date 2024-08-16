<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'excerpt' => $this->faker->word(),
            'content' => $this->faker->word(),
            'website' => $this->faker->word(),
            'thumbnail' => $this->faker->word(),
            'published_at' => null,
            'is_featured' => $this->faker->boolean(),
        ];
    }

    public function published(): ProjectFactory
    {
        return $this->state(fn() => [
            'published_at' => Carbon::now(),
        ]);
    }
}
