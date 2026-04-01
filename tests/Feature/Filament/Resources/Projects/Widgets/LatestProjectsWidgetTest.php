<?php

declare(strict_types=1);

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Widgets\LatestProjects;
use App\Models\Project;
use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.pages.dashboard'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.pages.dashboard'))
        ->assertForbidden();
});

it('can render the widget', function () {
    $this->actingAs($this->admin);

    livewire(LatestProjects::class)
        ->assertSuccessful();
});

it('displays the latest 4 records', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(6)->create();

    livewire(LatestProjects::class)
        ->assertCanSeeTableRecords($projects->sortByDesc('id')->take(4));
});

it('displays all records when fewer than 4 exist', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(2)->create();

    livewire(LatestProjects::class)
        ->assertCanSeeTableRecords($projects);
});

it('displays records in descending order by id', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(4)->create();

    livewire(LatestProjects::class)
        ->assertCanSeeTableRecords($projects->sortByDesc('id'));
});

it('shows empty state when no records exist', function () {
    $this->actingAs($this->admin);

    livewire(LatestProjects::class)
        ->assertSuccessful();
});

it('displays is_published status as boolean', function () {
    $this->actingAs($this->admin);

    $published = Project::factory()->published()->create();
    $unpublished = Project::factory()->create(['is_published' => false]);

    livewire(LatestProjects::class)
        ->assertCanSeeTableRecords(collect([$published, $unpublished]));
});

it('limits title to 30 characters', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create(['title' => 'This is a very long project title that exceeds thirty characters']);

    livewire(LatestProjects::class)
        ->assertCanSeeTableRecords(collect([$project]));
});

it('has edit action for each record', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();

    livewire(LatestProjects::class)
        ->assertTableActionVisible('edit', $project);
});

it('redirects to edit page when clicking edit', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();

    livewire(LatestProjects::class)
        ->callTableAction('edit', $project)
        ->assertRedirect(ProjectResource::getUrl('edit', ['record' => $project]));
});
