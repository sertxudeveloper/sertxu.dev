<?php

declare(strict_types=1);

use App\Filament\Resources\FormSubmissions\Pages\ViewFormSubmission;
use App\Models\FormSubmission;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\Testing\TestAction;

use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $submission = FormSubmission::factory()->create();

    $this->get(route('filament.admin.resources.form-submissions.view', $submission))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $submission = FormSubmission::factory()->create();

    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.form-submissions.view', $submission))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create();

    livewire(ViewFormSubmission::class, ['record' => $submission->id])
        ->assertSuccessful();
});

it('displays submission details', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Hello, this is a test message.',
    ]);

    livewire(ViewFormSubmission::class, ['record' => $submission->id])
        ->assertSchemaStateSet([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, this is a test message.',
        ]);
});

it('can delete a submission', function () {
    $this->actingAs($this->admin);

    $submission = FormSubmission::factory()->create();

    livewire(ViewFormSubmission::class, ['record' => $submission->id])
        ->callAction(TestAction::make(DeleteAction::class));

    assertSoftDeleted($submission);
});
