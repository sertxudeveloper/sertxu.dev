<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

final readonly class PostPreviewController
{
    /**
     * Get the post preview page.
     */
    public function __invoke(Request $request, Post $post): View
    {
        abort_unless($request->user(), 404);

        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
