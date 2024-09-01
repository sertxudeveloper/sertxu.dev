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
            'excerpt' => $this->faker->sentence(),
            'text' => $this->faker->paragraphs(asText: true),
            'website' => $this->faker->word(),
            'is_published' => false,
            'is_featured' => $this->faker->boolean(),
        ];
    }

    public function published(): ProjectFactory
    {
        return $this->state(fn() => [
            'is_published' => true,
        ]);
    }
}
