<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Project::create([
            'title' => 'Laravel Media Model package',
            'slug' => 'laravel-media-model-package',
            'excerpt' => 'Attach media files to your Laravel models and create media collections.',
            'content' => '',
            'website' => 'https://github.com/sertxudeveloper/laravel-media-model',
            'thumbnail' => 'projects/01J4SMJ6090ABAJMAKVZSJFB6D.png',
            'published_at' => now(),
            'is_featured' => false,
        ]);

        Project::create([
            'title' => 'Livewire combobox package',
            'slug' => 'livewire-combobox-package',
            'excerpt' => 'A searchable combobox for your Livewire forms.',
            'content' => '',
            'website' => 'https://github.com/sertxudeveloper/livewire-combobox',
            'thumbnail' => 'projects/01J4SMMC57D1Z55G05DVDZ43ZT.png',
            'published_at' => now(),
            'is_featured' => false,
        ]);

        Project::create([
            'title' => 'Laravel counters package',
            'slug' => 'laravel-counters-package',
            'excerpt' => 'Manage counters with year and series in your Laravel app.',
            'content' => '',
            'website' => 'https://github.com/sertxudeveloper/laravel-counters',
            'thumbnail' => 'projects/01J4SMNFPXA9G7PH5Y6WFRB4CQ.png',
            'published_at' => now(),
            'is_featured' => false,
        ]);

        Project::create([
            'title' => 'Cuelist',
            'slug' => 'cuelist',
            'excerpt' => 'Helps you coordinate your team in a live show.',
            'content' => '',
            'website' => 'https://cuelist.app',
            'thumbnail' => 'projects/01J4SMSWZ89XBVAG6KFQFP4DAY.png',
            'published_at' => now(),
            'is_featured' => true,
        ]);
    }

    public function down(): void
    {
        DB::table('projects')->truncate();
    }
};
