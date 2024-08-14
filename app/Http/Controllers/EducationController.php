<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Contracts\View\View;

class EducationController extends Controller
{
    /**
     * Get the index page.
     *
     * @return View
     */
    public function index(): View
    {
        $education = Education::query()->defaultOrder()->get();

        return view('education.index', [
            'education' => $education,
        ]);
    }
}
