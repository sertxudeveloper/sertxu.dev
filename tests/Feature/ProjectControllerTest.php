<?php

use App\Livewire\Projects;
use App\Models\Project;

it('can visit the project page', function () {
    $response = $this->get(route('projects.index'));

    $response->assertOk();
});

it('can visit a project', function () {
    $project = Project::factory()->create();

    $response = $this->get(route('projects.show', $project));

    $response->assertOk();
    $response->assertSee($project->title);
    $response->assertSee($project->content);
});

it('can load more projects at index', function () {
    Project::factory(20)->sequence(fn ($sequence) => ['title' => "Project {$sequence->index}"])->create();

    Livewire::test(Projects::class)
        ->assertSee('Project 2')
        ->assertDontSee('Project 9')
        ->assertDontSee('Project 11')

        ->call('loadMore')
        ->assertSee('Project 2')
        ->assertSee('Project 9')
        ->assertDontSee('Project 19')

        ->call('loadMore')
        ->assertSee('Project 2')
        ->assertSee('Project 9')
        ->assertSee('Project 19');
});
