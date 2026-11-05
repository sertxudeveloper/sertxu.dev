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
     * Get the post's index page.
     */
    public function index(Request $request): View
    {
        $tag = $request->input('tag') ?: null;
        $tag = is_string($tag) ? $tag : null;

        $search = $request->input('search');
        $search = is_string($search) ? $search : null;

        $query = Post::query()
            ->wherePublished()
            ->with('media');

        if ($tag) {
            $query->withAnyTags($tag);
        }

        if ($search) {
            $query->where(function (Builder $query) use ($search): void {
                $query->whereLike('title', "%{$search}%")
                    ->orWhereLike('excerpt', "%{$search}%")
                    ->orWhereLike('text', "%{$search}%");
            });
        }

        $posts = $query
            ->orderByDesc('published_at')
            ->paginate(perPage: 12)
            ->onEachSide(2);

        return view('posts.index', [
            'posts' => $posts,
            'search' => $search,
            'tag' => $tag,
        ]);
    }

    /**
     * Get the post's show page.
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
