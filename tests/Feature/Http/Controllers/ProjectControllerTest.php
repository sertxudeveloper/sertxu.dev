<?php

declare(strict_types=1);

use App\Models\Project;

it('can load project index page', function (): void {
    Project::factory()->published()->create(['title' => 'Project A', 'is_featured' => false, 'created_at' => '2024-01-01 00:00:00']);
    Project::factory()->published()->create(['title' => 'Project B', 'is_featured' => false, 'created_at' => '2024-01-02 00:00:00']);
    Project::factory()->published()->create(['title' => 'Project C', 'is_featured' => false, 'created_at' => '2024-01-03 00:00:00']);

    $this->get('/projects')
        ->assertOk()
        ->assertSeeText('My Projects')
        ->assertSeeTextInOrder([
            'Project C',
            'Project B',
            'Project A',
        ]);
});

it('shows a published project', function (): void {
    $project = Project::factory()->published()->create(['title' => 'Project A']);

    $this->get("/projects/$project->slug")
        ->assertOk()
        ->assertSeeText('Project A');
});

it('returns 404 for an unpublished project', function () {
    $project = Project::factory()->create();

    $this->get("/projects/$project->slug")
        ->assertNotFound();
});
