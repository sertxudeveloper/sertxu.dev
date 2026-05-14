<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Contracts\View\View;

final readonly class RedesignController
{
    /**
     * Get the home page.
     */
    public function __invoke(): View
    {
        $posts = Post::query()->wherePublished()->take(4)->get();
        $projects = Project::query()->wherePublished()->with('tags')->take(3)->get();

        return view('home-redesign.home', [
            'posts' => $posts,
            'projects' => $projects,
        ]);
    }
}
