<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\FormSubmission;
use App\Models\User;
use App\Notifications\FormSubmissionNotification;
use Illuminate\Http\RedirectResponse;

final readonly class ContactController
{
    /**
     * Get the contact form data and store it.
     */
    public function store(ContactFormRequest $request): RedirectResponse
    {
        $submission = new FormSubmission();
        $submission->fill($request->validated());
        $submission->save();

        User::query()
            ->where('is_admin', true)->get()->each
            ->notify(new FormSubmissionNotification($submission));

        return redirect()
            ->route('home')
            ->withFragment('contact')
            ->with('success', 'Thank you for contacting me! ');
    }
}
