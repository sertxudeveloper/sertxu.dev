<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::query()->latest()->get();

        return view('projects', [
            'projects' => $projects,
        ]);
    }

    public function index2(Request $request)
    {
        $projects = Project::query()->latest()->get();

        return view('projects2', [
            'projects' => $projects,
        ]);
    }
}
