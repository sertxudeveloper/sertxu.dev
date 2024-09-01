<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Project::create([
            'title' => 'Laravel Media Model package',
            'slug' => 'laravel-media-model-package',
            'excerpt' => 'Attach media files to your Laravel models and create media collections.',
            'text' => '',
            'website' => 'https://github.com/sertxudeveloper/laravel-media-model',
            'is_published' => true,
            'is_featured' => false,
        ]);

        Project::create([
            'title' => 'Livewire combobox package',
            'slug' => 'livewire-combobox-package',
            'excerpt' => 'A searchable combobox for your Livewire forms.',
            'text' => '',
            'website' => 'https://github.com/sertxudeveloper/livewire-combobox',
            'is_published' => true,
            'is_featured' => false,
        ]);

        Project::create([
            'title' => 'Laravel counters package',
            'slug' => 'laravel-counters-package',
            'excerpt' => 'Manage counters with year and series in your Laravel app.',
            'text' => '',
            'website' => 'https://github.com/sertxudeveloper/laravel-counters',
            'is_published' => true,
            'is_featured' => false,
        ]);

        Project::create([
            'title' => 'Cuelist',
            'slug' => 'cuelist',
            'excerpt' => 'Helps you coordinate your team in a live show.',
            'text' => '',
            'website' => 'https://cuelist.app',
            'is_published' => true,
            'is_featured' => true,
        ]);
    }

    public function down(): void
    {
        Project::truncate();
    }
};
