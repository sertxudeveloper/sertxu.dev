<?php

declare(strict_types=1);

use App\Filament\Resources\Education\EducationResource;
use App\Filament\Resources\Education\Widgets\LatestEducation;
use App\Models\Education;
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

    livewire(LatestEducation::class)
        ->assertSuccessful();
});

it('displays the latest 4 records', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(6)->create();

    livewire(LatestEducation::class)
        ->assertCanSeeTableRecords($education->sortByDesc('id')->take(4));
});

it('displays all records when fewer than 4 exist', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(2)->create();

    livewire(LatestEducation::class)
        ->assertCanSeeTableRecords($education);
});

it('displays records in descending order by id', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(4)->create();

    livewire(LatestEducation::class)
        ->assertCanSeeTableRecords($education->sortByDesc('id'));
});

it('shows empty state when no records exist', function () {
    $this->actingAs($this->admin);

    livewire(LatestEducation::class)
        ->assertSuccessful();
});

it('displays title column', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create(['title' => 'Master of Computer Science']);

    livewire(LatestEducation::class)
        ->assertCanSeeTableRecords(collect([$education]));
});

it('has edit action for each record', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();

    livewire(LatestEducation::class)
        ->assertTableActionVisible('edit', $education);
});

it('redirects to edit page when clicking edit', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();

    livewire(LatestEducation::class)
        ->callTableAction('edit', $education)
        ->assertRedirect(EducationResource::getUrl('edit', ['record' => $education]));
});
