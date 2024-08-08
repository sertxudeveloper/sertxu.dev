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
        $experiences = Experience::query()->orderByDesc('started_at')->take(4)->get();
        $education = Education::query()->orderByDesc('started_at')->take(4)->get();
        $posts = Post::query()->orderByDesc('published_at')->take(4)->get();
        $projects = Project::query()->orderByDesc('started_at')->take(4)->get();

        return view('welcome', [
            'experiences' => $experiences,
            'education' => $education,
            'posts' => $posts,
            'projects' => $projects,
        ]);
    }
}
