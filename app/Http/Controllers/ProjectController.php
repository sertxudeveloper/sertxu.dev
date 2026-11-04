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
        $tagInput = $request->input('tag');
        $tags = null;
        $tagLabel = null;

        if (is_string($tagInput)) {
            $tags = $tagInput;
            $tagLabel = $tagInput;
        } elseif (is_array($tagInput) && array_is_list($tagInput)) {
            $tagList = [];

            foreach ($tagInput as $tag) {
                if (! is_string($tag)) {
                    $tagList = null;
                    break;
                }

                $tagList[] = $tag;
            }

            if ($tagList !== null) {
                $tags = $tagList;
                $tagLabel = implode(', ', $tagList);
            }
        }

        $query = Project::query()
            ->wherePublished()
            ->defaultOrder();

        if ($tags) {
            $query->withAnyTags($tags);
        }

        $projects = $query
            ->paginate(perPage: 6)
            ->onEachSide(2);

        return view('projects.index', [
            'projects' => $projects,
            'tag' => $tagLabel,
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
