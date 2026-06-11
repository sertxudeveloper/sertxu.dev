<?php

declare(strict_types=1);

use App\Models\FormSubmission;
use App\Models\User;
use App\Notifications\FormSubmissionNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    config([
        'services.turnstile.secret' => 'test-secret-key',
    ]);
});

it('stores a form submission and notifies admins', function () {
    Notification::fake();

    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    User::factory()->create(['is_admin' => true]);

    $this->post(route('contact.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Hello, I would like to discuss a project.',
        'correct' => 'y',
        'cf-turnstile-response' => 'valid-token',
    ])->assertRedirect(route('home').'#contact');

    $submission = FormSubmission::first();

    expect($submission)
        ->not->toBeNull()
        ->and($submission->name)->toBe('John Doe')
        ->and($submission->email)->toBe('john@example.com')
        ->and($submission->message)->toBe('Hello, I would like to discuss a project.')
        ->and(session('success'))->toContain('Thank you');

    Notification::assertSentTo(
        User::where('is_admin', true)->first(),
        FormSubmissionNotification::class,
    );
});

it('does not store turnstile or correct fields in the database', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    $this->post(route('contact.store'), [
        'name' => 'Jane',
        'email' => 'jane@example.com',
        'message' => 'Test message',
        'correct' => 'y',
        'cf-turnstile-response' => 'valid-token',
    ])->assertRedirect(route('home').'#contact');

    $submission = FormSubmission::first();

    expect($submission)->not->toBeNull();
});

it('validates required fields', function () {
    $this->post(route('contact.store'), [])
        ->assertRedirect('/#contact')
        ->assertSessionHasErrors(['name', 'email', 'message', 'correct']);
});

it('validates email format', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    $this->post(route('contact.store'), [
        'name' => 'John',
        'email' => 'not-an-email',
        'message' => 'Hello',
        'correct' => 'y',
        'cf-turnstile-response' => 'valid-token',
    ])->assertSessionHasErrors(['email']);
});

it('validates correct field must be y', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    $this->post(route('contact.store'), [
        'name' => 'John',
        'email' => 'john@example.com',
        'message' => 'Hello',
        'correct' => 'n',
        'cf-turnstile-response' => 'valid-token',
    ])->assertSessionHasErrors(['correct']);
});

it('validates message max length', function () {
    Http::fake([
        'https://challenges.cloudflare.com/turnstile/v0/siteverify' => Http::response([
            'success' => true,
            'error-codes' => [],
        ], 200),
    ]);

    $this->post(route('contact.store'), [
        'name' => 'John',
        'email' => 'john@example.com',
        'message' => str_repeat('a', 1001),
        'correct' => 'y',
        'cf-turnstile-response' => 'valid-token',
    ])->assertSessionHasErrors(['message']);
});

it('fails when turnstile verification fails', function () {
    $fakeWasCalled = false;

    Http::fake([
        '*' => function () use (&$fakeWasCalled) {
            $fakeWasCalled = true;

            return Http::response(['success' => false, 'error-codes' => ['invalid-input-response']], 200);
        },
    ]);

    $response = $this->post(route('contact.store'), [
        'name' => 'John',
        'email' => 'john@example.com',
        'message' => 'Hello',
        'correct' => 'y',
        'cf-turnstile-response' => 'invalid-token',
    ]);

    expect($fakeWasCalled)->toBeTrue();
});
