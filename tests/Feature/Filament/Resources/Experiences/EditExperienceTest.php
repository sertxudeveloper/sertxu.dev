<?php

declare(strict_types=1);

use App\Filament\Resources\Experiences\Pages\EditExperience;
use App\Models\Experience;
use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $experience = Experience::factory()->create();

    $this->get(route('filament.admin.resources.experiences.edit', ['record' => $experience->id]))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $experience = Experience::factory()->create();

    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.experiences.edit', ['record' => $experience->id]))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();

    livewire(EditExperience::class, [
        'record' => $experience->id,
    ])
        ->assertSuccessful();
});

it('can load experience data into the form', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();

    livewire(EditExperience::class, [
        'record' => $experience->id,
    ])
        ->assertSchemaStateSet([
            'title' => $experience->title,
            'description' => $experience->description,
            'started_at' => $experience->started_at->format('Y-m-d'),
            'ended_at' => $experience->ended_at->format('Y-m-d'),
            'location' => $experience->location,
        ]);
});

it('can update an experience', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();
    $newExperienceData = Experience::factory()->make();

    livewire(EditExperience::class, [
        'record' => $experience->id,
    ])
        ->fillForm([
            'title' => $newExperienceData->title,
            'description' => $newExperienceData->description,
            'started_at' => $newExperienceData->started_at,
            'ended_at' => $newExperienceData->ended_at,
            'location' => $newExperienceData->location,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Experience::class, [
        'id' => $experience->id,
        'title' => $newExperienceData->title,
        'location' => $newExperienceData->location,
    ]);
});

it('can update an experience to be current (no end date)', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();

    livewire(EditExperience::class, [
        'record' => $experience->id,
    ])
        ->fillForm([
            'title' => $experience->title,
            'description' => $experience->description,
            'started_at' => $experience->started_at,
            'ended_at' => null,
            'location' => $experience->location,
        ])
        ->call('save')
        ->assertNotified();

    expect($experience->fresh()->ended_at)->toBeNull();
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();

    livewire(EditExperience::class, [
        'record' => $experience->id,
    ])
        ->fillForm([
            'title' => $experience->title,
            'description' => $experience->description,
            'started_at' => $experience->started_at,
            'ended_at' => $experience->ended_at,
            'location' => $experience->location,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`description` is required' => [['description' => null], ['description' => 'required']],
    '`started_at` is required' => [['started_at' => null], ['started_at' => 'required']],
    '`location` is required' => [['location' => null], ['location' => 'required']],
]);

it('can soft delete an experience', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();

    livewire(EditExperience::class, [
        'record' => $experience->id,
    ])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    expect($experience->fresh()->trashed())->toBeTrue();
});
