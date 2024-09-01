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
            'text' => $this->faker->paragraphs(asText: true),
            'is_published' => false,
            'published_at' => null
        ];
    }

    public function published(): PostFactory
    {
        return $this->state(fn() => [
            'is_published' => true,
            'published_at' => now()
        ]);
    }
}
