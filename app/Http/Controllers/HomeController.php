<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Post;
use App\Models\Project;

class HomeController extends Controller
{
    public function __invoke()
    {
        $posts = Post::query()->active()->defaultOrder()->take(4)->get();
        $projects = Project::query()->active()->defaultOrder()->take(8)->get();

        return view('welcome', [
            'posts' => $posts,
            'projects' => $projects,
        ]);
    }
}
