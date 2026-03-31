<?php

declare(strict_types=1);

use App\Filament\Resources\Projects\Pages\EditProject;
use App\Models\Project;
use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $project = Project::factory()->create();

    $this->get(route('filament.admin.resources.projects.edit', ['record' => $project->id]))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $project = Project::factory()->create();

    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.projects.edit', ['record' => $project->id]))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();

    livewire(EditProject::class, [
        'record' => $project->id,
    ])
        ->assertSuccessful();
});

it('can load project data into the form', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();

    livewire(EditProject::class, [
        'record' => $project->id,
    ])
        ->assertSchemaStateSet([
            'title' => $project->title,
            'slug' => $project->slug,
            'excerpt' => $project->excerpt,
            'website' => $project->website,
            'text' => $project->text,
            'is_published' => $project->is_published,
            'is_featured' => $project->is_featured,
        ]);
});

it('can update a project', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();
    $newProjectData = Project::factory()->make();

    livewire(EditProject::class, [
        'record' => $project->id,
    ])
        ->fillForm([
            'title' => $newProjectData->title,
            'slug' => $newProjectData->slug,
            'excerpt' => $newProjectData->excerpt,
            'website' => $newProjectData->website,
            'text' => $newProjectData->text,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Project::class, [
        'id' => $project->id,
        'title' => $newProjectData->title,
        'slug' => $newProjectData->slug,
        'excerpt' => $newProjectData->excerpt,
        'website' => $newProjectData->website,
        'text' => $newProjectData->text,
    ]);
});

it('can publish a project', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create(['is_published' => false]);

    livewire(EditProject::class, [
        'record' => $project->id,
    ])
        ->fillForm([
            'title' => $project->title,
            'slug' => $project->slug,
            'excerpt' => $project->excerpt,
            'is_published' => true,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Project::class, [
        'id' => $project->id,
        'is_published' => true,
    ]);
});

it('can feature a project', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create(['is_featured' => false]);

    livewire(EditProject::class, [
        'record' => $project->id,
    ])
        ->fillForm([
            'title' => $project->title,
            'slug' => $project->slug,
            'excerpt' => $project->excerpt,
            'is_featured' => true,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Project::class, [
        'id' => $project->id,
        'is_featured' => true,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();

    livewire(EditProject::class, [
        'record' => $project->id,
    ])
        ->fillForm([
            'title' => $project->title,
            'slug' => $project->slug,
            'excerpt' => $project->excerpt,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`slug` is required' => [['slug' => null], ['slug' => 'required']],
    '`excerpt` is required' => [['excerpt' => null], ['excerpt' => 'required']],
]);

it('can soft delete a project', function () {
    $this->actingAs($this->admin);

    $project = Project::factory()->create();

    livewire(EditProject::class, [
        'record' => $project->id,
    ])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    expect($project->fresh()->trashed())->toBeTrue();
});
