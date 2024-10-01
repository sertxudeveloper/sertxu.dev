<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProjectFactory extends Factory
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

    public function published(): self
    {
        return $this->state(fn (): array => [
            'is_published' => true,
        ]);
    }
}
