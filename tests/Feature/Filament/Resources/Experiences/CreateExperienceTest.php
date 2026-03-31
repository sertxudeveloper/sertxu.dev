<?php

declare(strict_types=1);

use App\Filament\Resources\Experiences\Pages\CreateExperience;
use App\Models\Experience;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.experiences.create'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.experiences.create'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(CreateExperience::class)
        ->assertSuccessful();
});

it('can create an experience', function () {
    $this->actingAs($this->admin);

    $newExperienceData = Experience::factory()->make();

    livewire(CreateExperience::class)
        ->fillForm([
            'title' => $newExperienceData->title,
            'description' => $newExperienceData->description,
            'started_at' => $newExperienceData->started_at,
            'ended_at' => $newExperienceData->ended_at,
            'location' => $newExperienceData->location,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Experience::class, [
        'title' => $newExperienceData->title,
        'location' => $newExperienceData->location,
    ]);
});

it('can create a current experience without end date', function () {
    $this->actingAs($this->admin);

    $newExperienceData = Experience::factory()->make();

    livewire(CreateExperience::class)
        ->fillForm([
            'title' => $newExperienceData->title,
            'description' => $newExperienceData->description,
            'started_at' => $newExperienceData->started_at,
            'location' => $newExperienceData->location,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Experience::class, [
        'title' => $newExperienceData->title,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $newExperienceData = Experience::factory()->make();

    livewire(CreateExperience::class)
        ->fillForm([
            'title' => $newExperienceData->title,
            'description' => $newExperienceData->description,
            'started_at' => $newExperienceData->started_at,
            'ended_at' => $newExperienceData->ended_at,
            'location' => $newExperienceData->location,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`description` is required' => [['description' => null], ['description' => 'required']],
    '`started_at` is required' => [['started_at' => null], ['started_at' => 'required']],
    '`location` is required' => [['location' => null], ['location' => 'required']],
]);
