<?php

declare(strict_types=1);

use App\Filament\Resources\Education\Pages\EditEducation;
use App\Models\Education;
use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $education = Education::factory()->create();

    $this->get(route('filament.admin.resources.education.edit', ['record' => $education->id]))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $education = Education::factory()->create();

    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.education.edit', ['record' => $education->id]))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();

    livewire(EditEducation::class, [
        'record' => $education->id,
    ])
        ->assertSuccessful();
});

it('can load education data into the form', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();

    livewire(EditEducation::class, [
        'record' => $education->id,
    ])
        ->assertSchemaStateSet([
            'title' => $education->title,
            'description' => $education->description,
            'started_at' => $education->started_at->format('Y-m-d'),
            'ended_at' => $education->ended_at->format('Y-m-d'),
            'location' => $education->location,
        ]);
});

it('can update an education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();
    $newEducationData = Education::factory()->make();

    livewire(EditEducation::class, [
        'record' => $education->id,
    ])
        ->fillForm([
            'title' => $newEducationData->title,
            'description' => $newEducationData->description,
            'started_at' => $newEducationData->started_at,
            'ended_at' => $newEducationData->ended_at,
            'location' => $newEducationData->location,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Education::class, [
        'id' => $education->id,
        'title' => $newEducationData->title,
        'location' => $newEducationData->location,
    ]);
});

it('can update an education to be current (no end date)', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();

    livewire(EditEducation::class, [
        'record' => $education->id,
    ])
        ->fillForm([
            'title' => $education->title,
            'description' => $education->description,
            'started_at' => $education->started_at,
            'ended_at' => null,
            'location' => $education->location,
        ])
        ->call('save')
        ->assertNotified();

    expect($education->fresh()->ended_at)->toBeNull();
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();

    livewire(EditEducation::class, [
        'record' => $education->id,
    ])
        ->fillForm([
            'title' => $education->title,
            'description' => $education->description,
            'started_at' => $education->started_at,
            'ended_at' => $education->ended_at,
            'location' => $education->location,
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

it('can soft delete an education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();

    livewire(EditEducation::class, [
        'record' => $education->id,
    ])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    expect($education->fresh()->trashed())->toBeTrue();
});
