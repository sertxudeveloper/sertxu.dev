<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FormSubmission;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FormSubmissionFactory extends Factory
{
    protected $model = FormSubmission::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'message' => $this->faker->paragraph(),
        ];
    }
}
