<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $posts = Post::published()->take(4)->get();
        $projects = Project::published()->take(8)->get();

        return view('home', [
            'posts' => $posts,
            'projects' => $projects,
        ]);
    }
}
