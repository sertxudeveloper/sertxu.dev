<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

final class PostPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Post $post) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Post Published: '.$this->post->title)
            ->greeting('Hello!')
            ->line('A new post has been published on our blog.')
            ->line('Title: '.$this->post->title)
            ->action('Read Post', route('posts.show', $this->post))
            ->line('')
            ->line('If you want to share it on LinkedIn, copy the following content:')
            ->line('')
            ->line($this->post->title)
            ->line(Str::limit($this->post->text, 300))
            ->line($this->post->linkedinUrl())
            ->line('')
            ->salutation('Until next time!');

    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
