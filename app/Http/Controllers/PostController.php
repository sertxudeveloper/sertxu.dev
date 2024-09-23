<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class PostController extends Controller
{
    /**
     * Get the show page.
     *
     * @param Post $post
     * @return View
     */
    public function show(Post $post): View
    {
        abort_if(! $post->isPublished(), 404);

        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
