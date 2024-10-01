<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Contracts\View\View;

final class ExperienceController
{
    /**
     * Get the index page.
     */
    public function index(): View
    {
        $experiences = Experience::query()->defaultOrder()->get();

        return view('experience.index', [
            'experiences' => $experiences,
        ]);
    }
}
