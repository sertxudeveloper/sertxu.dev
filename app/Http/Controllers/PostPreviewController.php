<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class PostPreviewController extends Controller
{
    public function __invoke(Post $post): View
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
