<?php

declare(strict_types=1);

use App\Models\FormSubmission;
use App\Models\User;
use App\Notifications\FormSubmissionNotification;
use Illuminate\Support\Facades\Notification;

it('sends the notification via mail', function () {
    $submission = FormSubmission::factory()->create();
    $notification = new FormSubmissionNotification($submission);

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
});

it('implements the ShouldQueue contract', function () {
    $notification = new FormSubmissionNotification(FormSubmission::factory()->create());

    expect($notification)->toBeInstanceOf(Illuminate\Contracts\Queue\ShouldQueue::class);
});

it('can be sent to a user', function () {
    Notification::fake();

    $user = User::factory()->create();
    $submission = FormSubmission::factory()->create();

    $user->notify(new FormSubmissionNotification($submission));

    Notification::assertSentTo($user, FormSubmissionNotification::class);
});

it('builds a mail message with the submission details', function () {
    $submission = FormSubmission::factory()->create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'message' => 'Hello, I want to discuss a project.',
    ]);

    $notification = new FormSubmissionNotification($submission);
    $mail = $notification->toMail(User::factory()->create());

    expect($mail->subject)->toBe('New form submission')
        ->and($mail->introLines)->toContain('You have received a new form submission.')
        ->and($mail->introLines)->toContain('Name: John Doe')
        ->and($mail->introLines)->toContain('Email: john@example.com')
        ->and($mail->introLines)->toContain('Message:')
        ->and($mail->introLines)->toContain('Hello, I want to discuss a project.');
});
