<?php

declare(strict_types=1);

use App\Filament\Resources\Education\Pages\CreateEducation;
use App\Models\Education;
use App\Models\User;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.education.create'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.education.create'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(CreateEducation::class)
        ->assertSuccessful();
});

it('can create an education', function () {
    $this->actingAs($this->admin);

    $newEducationData = Education::factory()->make();

    livewire(CreateEducation::class)
        ->fillForm([
            'title' => $newEducationData->title,
            'description' => $newEducationData->description,
            'started_at' => $newEducationData->started_at,
            'ended_at' => $newEducationData->ended_at,
            'location' => $newEducationData->location,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Education::class, [
        'title' => $newEducationData->title,
        'location' => $newEducationData->location,
    ]);
});

it('can create a current education without end date', function () {
    $this->actingAs($this->admin);

    $newEducationData = Education::factory()->make();

    livewire(CreateEducation::class)
        ->fillForm([
            'title' => $newEducationData->title,
            'description' => $newEducationData->description,
            'started_at' => $newEducationData->started_at,
            'location' => $newEducationData->location,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Education::class, [
        'title' => $newEducationData->title,
    ]);
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $newEducationData = Education::factory()->make();

    livewire(CreateEducation::class)
        ->fillForm([
            'title' => $newEducationData->title,
            'description' => $newEducationData->description,
            'started_at' => $newEducationData->started_at,
            'ended_at' => $newEducationData->ended_at,
            'location' => $newEducationData->location,
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
