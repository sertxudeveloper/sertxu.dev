<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Contracts\View\View;

final readonly class HomeController
{
    public function __invoke(): View
    {
        $posts = Post::published()->take(4)->get();
        $projects = Project::published()->take(8)->get();

        return view('home.home', [
            'posts' => $posts,
            'projects' => $projects,
        ]);
    }
}
