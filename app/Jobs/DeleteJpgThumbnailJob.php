<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class DeleteJpgThumbnailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Post $post) {}

    public function handle(): void
    {
        // Remove the jpg thumbnail, once it's posted to Medium it's no longer required. (Medium doesn't support .webp)
        $this->post->clearMediaCollection('thumbnail-jpg');
    }
}
