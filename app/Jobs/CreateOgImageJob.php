<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\Browsershot\Browsershot;

final class CreateOgImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public function __construct(
        public Post $post
    ) {}

    public function handle(): void
    {
        try {
            // Generate the html thumbnail
            $html = view('posts.thumbnail', ['post' => $this->post])->render();

            $base64Image = Browsershot::html($html)
                ->addChromiumArguments(['disable-gpu'])
                ->noSandbox()
                ->waitUntilNetworkIdle()
                ->setScreenshotType('jpg')
                ->windowSize(640, 360)
                ->base64Screenshot();

            $this->post
                ->addMediaFromBase64($base64Image)
                ->usingFileName($this->post->slug.'.jpg')
                ->toMediaCollection('thumbnail');
        } catch (Exception $exception) {
            report($exception);
        }
    }
}
