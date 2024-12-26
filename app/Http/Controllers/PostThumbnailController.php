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

        return response()->file(
            file: $post->getFirstMediaPath('thumbnail', 'thumbnail-jpg'),
            headers: [
                'Content-Type' => 'image/jpeg',
                'Content-Disposition' => 'inline; filename="'.$post->slug.'.jpg"',
            ],
        );
    }
}
