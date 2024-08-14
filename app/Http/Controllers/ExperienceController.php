<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Contracts\View\View;

class ExperienceController extends Controller
{
    /**
     * Get the index page.
     *
     * @return View
     */
    public function index(): View
    {
        $experiences = Experience::query()->defaultOrder()->get();

        return view('experience.index', [
            'experiences' => $experiences,
        ]);
    }
}
