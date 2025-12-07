<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;

final readonly class ProjectController
{
    /**
     * Get the project show page.
     */
    public function show(Project $project): View
    {
        abort_unless($project->isPublished(), 404);

        return view('projects.show', [
            'project' => $project,
        ]);
    }
}
