<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'excerpt' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(asText: true),
            'is_published' => false,
            'views' => $this->faker->randomNumber(),
        ];
    }

    public function published(): PostFactory
    {
        return $this->state(fn() => [
            'is_published' => true,
        ]);
    }
}
