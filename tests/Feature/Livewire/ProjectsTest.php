<?php

declare(strict_types=1);

use App\Livewire\Projects;
use App\Models\Project;

it('renders successfully', function () {
    livewire(Projects::class)
        ->assertSuccessful();
});

it('displays published projects', function () {
    $projects = Project::factory()->published()->count(3)->create();

    livewire(Projects::class)
        ->assertSee($projects->first()->title);
});

it('does not display unpublished projects', function () {
    Project::factory()->create(['is_published' => false]);

    livewire(Projects::class)
        ->assertDontSee('Unpublished');
});

it('filters projects by tag', function () {
    $project = Project::factory()->published()->create();
    $project->attachTags(['laravel']);

    livewire(Projects::class, ['tag' => 'laravel'])
        ->assertSee($project->title);
});

it('clears selected tag', function () {
    $component = livewire(Projects::class, ['tag' => 'laravel']);

    $component->call('clearSelectedTag');

    $component->assertSet('tag', '');
});

it('paginates projects', function () {
    Project::factory()->published()->count(15)->create();

    livewire(Projects::class)
        ->assertSuccessful();
});
