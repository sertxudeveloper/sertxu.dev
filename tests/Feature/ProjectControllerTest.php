<?php

use App\Livewire\Projects;
use App\Models\Project;

it('can see the project page', function () {
    Project::factory(3)->create();

    $response = $this->get(route('projects.index'));

    $response->assertOk();
});

it('can visit a project', function () {
    $project = Project::factory()->create();

    $response = $this->get(route('projects.show', $project));

    $response->assertOk();
    $response->assertSee($project->title);
});

it('can load more projects at index', function () {
    Project::factory(20)
        ->sequence(fn ($sequence) => ['title' => "Project {$sequence->index}"])
        ->published()
        ->create();

    Livewire::test(Projects::class)
        ->assertSee('Project 19')
        ->assertDontSee('Project 9')
        ->assertDontSee('Project 2')

        ->call('loadMore')
        ->assertSee('Project 19')
        ->assertSee('Project 9')
        ->assertDontSee('Project 2')

        ->call('loadMore')
        ->assertSee('Project 19')
        ->assertSee('Project 9')
        ->assertSee('Project 2');
});
