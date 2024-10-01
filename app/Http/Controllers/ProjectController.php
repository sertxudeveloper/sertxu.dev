<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;

final class ProjectController extends Controller
{
    /**
     * Get the show page.
     */
    public function show(Project $project): View
    {
        return view('projects.show', [
            'project' => $project,
        ]);
    }
}
