<?php

declare(strict_types=1);

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Models\Project;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.projects.create'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.projects.create'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(CreateProject::class)
        ->assertSuccessful();
});

it('can create a project', function () {
    $this->actingAs($this->admin);

    $newProjectData = Project::factory()->make();

    livewire(CreateProject::class)
        ->fillForm([
            'title' => $newProjectData->title,
            'slug' => $newProjectData->slug,
            'excerpt' => $newProjectData->excerpt,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Project::class, [
        'title' => $newProjectData->title,
        'slug' => $newProjectData->slug,
        'excerpt' => $newProjectData->excerpt,
    ]);
});

it('can create a published project', function () {
    $this->actingAs($this->admin);

    $newProjectData = Project::factory()->make();

    livewire(CreateProject::class)
        ->fillForm([
            'title' => $newProjectData->title,
            'slug' => $newProjectData->slug,
            'excerpt' => $newProjectData->excerpt,
            'is_published' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Project::class, [
        'title' => $newProjectData->title,
        'is_published' => true,
    ]);
});

it('can create a featured project', function () {
    $this->actingAs($this->admin);

    $newProjectData = Project::factory()->make();

    livewire(CreateProject::class)
        ->fillForm([
            'title' => $newProjectData->title,
            'slug' => $newProjectData->slug,
            'excerpt' => $newProjectData->excerpt,
            'is_featured' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Project::class, [
        'title' => $newProjectData->title,
        'is_featured' => true,
    ]);
});

it('can create a project with all fields', function () {
    $this->actingAs($this->admin);

    $newProjectData = Project::factory()->make();

    livewire(CreateProject::class)
        ->fillForm([
            'title' => $newProjectData->title,
            'slug' => $newProjectData->slug,
            'excerpt' => $newProjectData->excerpt,
            'website' => $newProjectData->website,
            'text' => $newProjectData->text,
            'is_published' => true,
            'is_featured' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Project::class, [
        'title' => $newProjectData->title,
        'slug' => $newProjectData->slug,
        'excerpt' => $newProjectData->excerpt,
        'website' => $newProjectData->website,
        'text' => $newProjectData->text,
        'is_published' => true,
        'is_featured' => true,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $newProjectData = Project::factory()->make();

    livewire(CreateProject::class)
        ->fillForm([
            'title' => $newProjectData->title,
            'slug' => $newProjectData->slug,
            'excerpt' => $newProjectData->excerpt,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`slug` is required' => [['slug' => null], ['slug' => 'required']],
    '`excerpt` is required' => [['excerpt' => null], ['excerpt' => 'required']],
]);
