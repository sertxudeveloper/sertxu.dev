<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

final class PostPreviewController extends Controller
{
    public function __invoke(Post $post): View
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
