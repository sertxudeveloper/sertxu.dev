<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

final class PostController
{
    /**
     * Get the show page.
     */
    public function show(Post $post): View
    {
        abort_if(! $post->isPublished(), 404);

        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
