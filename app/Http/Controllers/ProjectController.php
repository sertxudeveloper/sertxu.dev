<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final readonly class ProjectController
{
    /**
     * Get the projects index page.
     */
    public function index(Request $request): View
    {
        $projects = Project::query()
            ->wherePublished()
            ->when($request->has('tag'), fn ($query) => $query->withAnyTags($request->input('tag')))
            ->defaultOrder()
            ->paginate(perPage: 6);

        return view('projects.index', [
            'projects' => $projects,
        ]);
    }

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
