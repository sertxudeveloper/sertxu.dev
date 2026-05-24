<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\FormSubmission;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class FormSubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly FormSubmission $submission,
    ) {}

    public function via(User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New form submission')
            ->greeting('Hello!')
            ->line('You have received a new form submission.')
            ->line('Name: '.$this->submission->name)
            ->line('Email: '.$this->submission->email)
            ->line('Message:')
            ->line($this->submission->message)
            ->line('')
            ->salutation('Best regards,');
    }
}
