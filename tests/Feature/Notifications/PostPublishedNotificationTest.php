<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostPublishedNotification;
use Illuminate\Support\Facades\Notification;

describe('PostPublishedNotification', function () {
    it('can be sent to a user', function () {
        Notification::fake();

        $user = User::factory()->create(['is_admin' => true]);
        $post = Post::factory()->published()->create();

        $user->notify(new PostPublishedNotification($post));

        Notification::assertSentTo($user, PostPublishedNotification::class);
    });

    it('contains the post title in the notification', function () {
        $user = User::factory()->create();
        $post = Post::factory()->published()->create(['title' => 'My Awesome Post']);

        $notification = new PostPublishedNotification($post);

        expect($notification->post->title)->toBe('My Awesome Post');
    });

    it('generates correct mail content', function () {
        $user = User::factory()->create(['email' => 'admin@example.com']);
        $post = Post::factory()->published()->create(['title' => 'Test Post']);

        $notification = new PostPublishedNotification($post);
        $mailMessage = $notification->toMail($user);

        expect($mailMessage->subject)->toContain('Test Post')
            ->and($mailMessage->actionUrl)->toContain($post->slug);
    });

    it('renders mail when the post text is missing', function () {
        $user = User::factory()->create();
        $post = Post::factory()->published()->create(['text' => null]);

        $mailMessage = (new PostPublishedNotification($post))->toMail($user);

        expect($mailMessage)->toBeInstanceOf(Illuminate\Notifications\Messages\MailMessage::class);
    });
});
