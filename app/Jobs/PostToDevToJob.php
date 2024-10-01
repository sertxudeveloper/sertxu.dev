<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use App\Services\DevTo\DevTo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

final class PostToDevToJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Post $post) {}

    #[NoReturn]
    public function handle(DevTo $devTo): void
    {
        if ($this->post->posted_on_dev) {
            return;
        }

        $devTo->writePost(
            title: $this->post->title,
            markdown: $this->post->text,
            imageUrl: $this->post->getFirstMediaUrl('thumbnail'),
            canonicalUrl: $this->post->url(),
            description: Str::limit($this->post->text, 250),
            tags: $this->post->tags->pluck('name')->toArray(),
        );

        $this->post->updateQuietly(['posted_on_dev' => true]);
    }
}
