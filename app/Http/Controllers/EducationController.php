<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Contracts\View\View;

final readonly class EducationController
{
    /**
     * Get the index page.
     */
    public function index(): View
    {
        $education = Education::query()->defaultOrder()->get();

        return view('education.index', [
            'education' => $education,
        ]);
    }
}
