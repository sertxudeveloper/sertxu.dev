<?php

declare(strict_types=1);

use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Models\Project;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.projects.index'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.projects.index'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(ListProjects::class)
        ->assertSuccessful();
});

it('can list projects', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(5)->create();

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects);
});

it('can search projects by title', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(5)->create();

    $searchTerm = $projects->first()->title;

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($projects->where('title', $searchTerm));
});

it('can sort projects by title', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(5)->create();

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->sortTable('title')
        ->assertCanSeeTableRecords($projects);
});

it('can sort projects by is_featured', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(5)->create();

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->sortTable('is_featured')
        ->assertCanSeeTableRecords($projects);
});

it('can sort projects by is_published', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(5)->create();

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->sortTable('is_published')
        ->assertCanSeeTableRecords($projects);
});

it('can filter trashed projects', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();
    $project->delete();

    livewire(ListProjects::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$project]));
});

it('can bulk delete projects', function () {
    $this->actingAs($this->admin);

    $projects = Project::factory()->count(3)->create();

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->selectTableRecords($projects)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified()
        ->assertCanNotSeeTableRecords($projects);

    $projects->each(fn (Project $project) => assertSoftDeleted($project));
});

it('can bulk restore trashed projects', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();
    $project->delete();

    livewire(ListProjects::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$project]))
        ->selectTableRecords(collect([$project]))
        ->callAction(TestAction::make(RestoreBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect($project->fresh()->trashed())->toBeFalse();
});

it('can bulk force delete trashed projects', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();
    $project->delete();

    livewire(ListProjects::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$project]))
        ->selectTableRecords(collect([$project]))
        ->callAction(TestAction::make(ForceDeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect(Project::find($project->id))->toBeNull();
});

it('can restore a soft deleted project', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();
    $project->delete();

    livewire(ListProjects::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$project]))
        ->callTableAction('restore', $project)
        ->assertNotified();

    expect($project->fresh()->trashed())->toBeFalse();
});

it('can force delete a soft deleted project', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();
    $project->delete();

    livewire(ListProjects::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$project]))
        ->callTableAction('forceDelete', $project)
        ->assertNotified();

    assertDatabaseMissing($project);
});

it('can soft delete a single project', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords(collect([$project]))
        ->callTableAction('delete', $project)
        ->assertNotified();

    assertSoftDeleted($project);
});
