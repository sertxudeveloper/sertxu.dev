<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\FormSubmission;
use App\Models\User;
use App\Notifications\FormSubmissionNotification;
use Illuminate\Http\Request;

final readonly class ContactController
{
    /**
     * Get the contact form data and store it.
     */
    public function store(Request $request): void
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $submission = new FormSubmission();
        $submission->fill($validated);
        $submission->save();

        User::query()->where('is_admin', true)->get()->each->notify(new FormSubmissionNotification($submission));
    }
}
