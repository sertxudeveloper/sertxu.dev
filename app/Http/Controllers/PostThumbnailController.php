<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;

final readonly class PostThumbnailController
{
    /**
     * Serve the post thumbnail.
     */
    public function __invoke(Post $post)
    {
        abort_unless($post->is_published, 404);

        return response()
            ->redirectTo($post->getFirstMediaUrl('thumbnail', 'thumbnail-jpg'), 301)
            ->header('Cache-Control', 'public, max-age=31536000')
            ->header('Vary', 'Accept');
    }
}
