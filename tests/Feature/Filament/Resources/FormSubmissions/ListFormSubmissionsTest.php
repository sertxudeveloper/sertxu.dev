<?php

declare(strict_types=1);

use App\Filament\Resources\FormSubmissions\Pages\ListFormSubmissions;
use App\Models\FormSubmission;
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
    $this->get(route('filament.admin.resources.form-submissions.index'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.form-submissions.index'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(ListFormSubmissions::class)
        ->assertSuccessful();
});

it('can list form submissions', function () {
    $this->actingAs($this->admin);

    $submissions = FormSubmission::factory()->count(5)->create();

    livewire(ListFormSubmissions::class)
        ->assertCanSeeTableRecords($submissions);
});

it('can search submissions by name', function () {
    $this->actingAs($this->admin);

    $submissions = FormSubmission::factory()->count(5)->create();
    $searchTerm = $submissions->first()->name;

    livewire(ListFormSubmissions::class)
        ->assertCanSeeTableRecords($submissions)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($submissions->where('name', $searchTerm));
});

it('can search submissions by email', function () {
    $this->actingAs($this->admin);

    $submissions = FormSubmission::factory()->count(5)->create();
    $searchTerm = $submissions->first()->email;

    livewire(ListFormSubmissions::class)
        ->assertCanSeeTableRecords($submissions)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($submissions->where('email', $searchTerm));
});

it('can sort submissions by created_at', function () {
    $this->actingAs($this->admin);

    $submissions = FormSubmission::factory()->count(5)->create();

    livewire(ListFormSubmissions::class)
        ->assertCanSeeTableRecords($submissions)
        ->sortTable('created_at')
        ->assertCanSeeTableRecords($submissions);
});

it('can filter trashed submissions', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create();
    $submission->delete();

    livewire(ListFormSubmissions::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$submission]));
});

it('can bulk delete submissions', function () {
    $this->actingAs($this->admin);

    $submissions = FormSubmission::factory()->count(3)->create();

    livewire(ListFormSubmissions::class)
        ->assertCanSeeTableRecords($submissions)
        ->selectTableRecords($submissions)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified()
        ->assertCanNotSeeTableRecords($submissions);

    $submissions->each(fn (FormSubmission $submission) => assertSoftDeleted($submission));
});

it('can bulk restore trashed submissions', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create();
    $submission->delete();

    livewire(ListFormSubmissions::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$submission]))
        ->selectTableRecords(collect([$submission]))
        ->callAction(TestAction::make(RestoreBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect($submission->fresh()->trashed())->toBeFalse();
});

it('can bulk force delete trashed submissions', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create();
    $submission->delete();

    livewire(ListFormSubmissions::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$submission]))
        ->selectTableRecords(collect([$submission]))
        ->callAction(TestAction::make(ForceDeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect(FormSubmission::find($submission->id))->toBeNull();
});

it('can restore a soft deleted submission', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create();
    $submission->delete();

    livewire(ListFormSubmissions::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$submission]))
        ->callTableAction('restore', $submission)
        ->assertNotified();

    expect($submission->fresh()->trashed())->toBeFalse();
});

it('can force delete a soft deleted submission', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create();
    $submission->delete();

    livewire(ListFormSubmissions::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$submission]))
        ->callTableAction('forceDelete', $submission)
        ->assertNotified();

    expect(FormSubmission::find($submission->id))->toBeNull();
});

it('can soft delete a single submission', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create();

    livewire(ListFormSubmissions::class)
        ->assertCanSeeTableRecords(collect([$submission]))
        ->callTableAction('delete', $submission)
        ->assertNotified();

    assertSoftDeleted($submission);
});
