<?php

declare(strict_types=1);

use App\Filament\Resources\Experiences\Pages\ListExperiences;
use App\Models\Experience;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.experiences.index'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.experiences.index'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(ListExperiences::class)
        ->assertSuccessful();
});

it('can list experiences', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(5)->create();

    livewire(ListExperiences::class)
        ->assertCanSeeTableRecords($experiences);
});

it('can search experiences by title', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(5)->create();

    $searchTerm = $experiences->first()->title;

    livewire(ListExperiences::class)
        ->assertCanSeeTableRecords($experiences)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($experiences->where('title', $searchTerm));
});

it('can search experiences by location', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(5)->create();

    $searchTerm = $experiences->first()->location;

    livewire(ListExperiences::class)
        ->assertCanSeeTableRecords($experiences)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($experiences->where('location', $searchTerm));
});

it('can sort experiences by started_at', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(5)->create();

    livewire(ListExperiences::class)
        ->assertCanSeeTableRecords($experiences)
        ->sortTable('started_at')
        ->assertCanSeeTableRecords($experiences);
});

it('can sort experiences by ended_at', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(5)->create();

    livewire(ListExperiences::class)
        ->assertCanSeeTableRecords($experiences)
        ->sortTable('ended_at')
        ->assertCanSeeTableRecords($experiences);
});

it('can filter trashed experiences', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();
    $experience->delete();

    livewire(ListExperiences::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$experience]));
});

it('can bulk delete experiences', function () {
    $this->actingAs($this->admin);

    $experiences = Experience::factory()->count(3)->create();

    livewire(ListExperiences::class)
        ->assertCanSeeTableRecords($experiences)
        ->selectTableRecords($experiences)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified()
        ->assertCanNotSeeTableRecords($experiences);

    $experiences->each(fn (Experience $experience) => assertSoftDeleted($experience));
});

it('can bulk restore trashed experiences', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();
    $experience->delete();

    livewire(ListExperiences::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$experience]))
        ->selectTableRecords(collect([$experience]))
        ->callAction(TestAction::make(RestoreBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect($experience->fresh()->trashed())->toBeFalse();
});

it('can bulk force delete trashed experiences', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();
    $experience->delete();

    livewire(ListExperiences::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$experience]))
        ->selectTableRecords(collect([$experience]))
        ->callAction(TestAction::make(ForceDeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect(Experience::find($experience->id))->toBeNull();
});

it('can restore a soft deleted experience', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();
    $experience->delete();

    livewire(ListExperiences::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$experience]))
        ->callTableAction('restore', $experience)
        ->assertNotified();

    expect($experience->fresh()->trashed())->toBeFalse();
});

it('can force delete a soft deleted experience', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();
    $experience->delete();

    livewire(ListExperiences::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$experience]))
        ->callTableAction('forceDelete', $experience)
        ->assertNotified();

    expect(Experience::find($experience->id))->toBeNull();
});

it('can soft delete a single experience', function () {
    $this->actingAs($this->admin);

    $experience = Experience::factory()->create();

    livewire(ListExperiences::class)
        ->assertCanSeeTableRecords(collect([$experience]))
        ->callTableAction('delete', $experience)
        ->assertNotified();

    assertSoftDeleted($experience);
});
