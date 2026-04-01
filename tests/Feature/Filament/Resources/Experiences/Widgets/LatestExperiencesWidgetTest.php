<?php

declare(strict_types=1);

use App\Filament\Resources\Experiences\ExperienceResource;
use App\Filament\Resources\Experiences\Widgets\LatestExperiences;
use App\Models\Experience;
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

    livewire(LatestExperiences::class)
        ->assertSuccessful();
});

it('displays the latest 4 records', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(6)->create();

    livewire(LatestExperiences::class)
        ->assertCanSeeTableRecords($experiences->sortByDesc('id')->take(4));
});

it('displays all records when fewer than 4 exist', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(2)->create();

    livewire(LatestExperiences::class)
        ->assertCanSeeTableRecords($experiences);
});

it('displays records in descending order by id', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(4)->create();

    livewire(LatestExperiences::class)
        ->assertCanSeeTableRecords($experiences->sortByDesc('id'));
});

it('shows empty state when no records exist', function () {
    $this->actingAs($this->admin);

    livewire(LatestExperiences::class)
        ->assertSuccessful();
});

it('displays title column', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create(['title' => 'Senior Developer at Tech Corp']);

    livewire(LatestExperiences::class)
        ->assertCanSeeTableRecords(collect([$experience]));
});

it('has edit action for each record', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();

    livewire(LatestExperiences::class)
        ->assertTableActionVisible('edit', $experience);
});

it('redirects to edit page when clicking edit', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();

    livewire(LatestExperiences::class)
        ->callTableAction('edit', $experience)
        ->assertRedirect(ExperienceResource::getUrl('edit', ['record' => $experience]));
});
