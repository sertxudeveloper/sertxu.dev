<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final readonly class PostController
{
    /**
     * Get the post index page.
     */
    public function index(Request $request): View
    {
        $posts = Post::query()
            ->wherePublished()
            ->with('media')
            ->when($request->has('tag'), fn (Builder $query) => $query->withAnyTags($request->input('tag')))
            ->when($request->has('query'), fn (Builder $query) => $query->where(function (Builder $query) use ($request): void {
                $query->whereLike('title', "%{$request->input('query')}%")
                    ->orWhereLike('text', "%{$request->input('query')}%");
            }))
            ->orderByDesc('published_at')
            ->paginate(perPage: 9);

        return view('posts.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Get the post show page.
     */
    public function show(Post $post): View
    {
        abort_unless($post->isPublished(), 404);

        return view('posts.show', [
            'post' => $post,
            'relatedPosts' => $post->relatedPosts(),
        ]);
    }
}
