<?php

declare(strict_types=1);

use App\Filament\Resources\Education\Pages\ListEducation;
use App\Models\Education;
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
    $this->get(route('filament.admin.resources.education.index'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.education.index'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(ListEducation::class)
        ->assertSuccessful();
});

it('can list education entries', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(5)->create();

    livewire(ListEducation::class)
        ->assertCanSeeTableRecords($education);
});

it('can search education by title', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(5)->create();

    $searchTerm = $education->first()->title;

    livewire(ListEducation::class)
        ->assertCanSeeTableRecords($education)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($education->where('title', $searchTerm));
});

it('can search education by location', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(5)->create();

    $searchTerm = $education->first()->location;

    livewire(ListEducation::class)
        ->assertCanSeeTableRecords($education)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($education->where('location', $searchTerm));
});

it('can sort education by started_at', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(5)->create();

    livewire(ListEducation::class)
        ->assertCanSeeTableRecords($education)
        ->sortTable('started_at')
        ->assertCanSeeTableRecords($education);
});

it('can sort education by ended_at', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(5)->create();

    livewire(ListEducation::class)
        ->assertCanSeeTableRecords($education)
        ->sortTable('ended_at')
        ->assertCanSeeTableRecords($education);
});

it('can filter trashed education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();
    $education->delete();

    livewire(ListEducation::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$education]));
});

it('can bulk delete education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->count(3)->create();

    livewire(ListEducation::class)
        ->assertCanSeeTableRecords($education)
        ->selectTableRecords($education)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified()
        ->assertCanNotSeeTableRecords($education);

    $education->each(fn (Education $education) => assertSoftDeleted($education));
});

it('can bulk restore trashed education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();
    $education->delete();

    livewire(ListEducation::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$education]))
        ->selectTableRecords(collect([$education]))
        ->callAction(TestAction::make(RestoreBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect($education->fresh()->trashed())->toBeFalse();
});

it('can bulk force delete trashed education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();
    $education->delete();

    livewire(ListEducation::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$education]))
        ->selectTableRecords(collect([$education]))
        ->callAction(TestAction::make(ForceDeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect(Education::find($education->id))->toBeNull();
});

it('can restore a soft deleted education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();
    $education->delete();

    livewire(ListEducation::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$education]))
        ->callTableAction('restore', $education)
        ->assertNotified();

    expect($education->fresh()->trashed())->toBeFalse();
});

it('can force delete a soft deleted education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();
    $education->delete();

    livewire(ListEducation::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$education]))
        ->callTableAction('forceDelete', $education)
        ->assertNotified();

    expect(Education::find($education->id))->toBeNull();
});

it('can soft delete a single education', function () {
    $this->actingAs($this->admin);

    $education = Education::factory()->create();

    livewire(ListEducation::class)
        ->assertCanSeeTableRecords(collect([$education]))
        ->callTableAction('delete', $education)
        ->assertNotified();

    assertSoftDeleted($education);
});
