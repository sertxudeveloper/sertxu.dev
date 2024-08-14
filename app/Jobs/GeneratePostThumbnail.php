<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;

class GeneratePostThumbnail implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Post $post,
    )
    {
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->post->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->post->thumbnail) {
            Storage::disk('public')->delete($this->post->thumbnail);
            $this->post->updateQuietly(['thumbnail' => null]);
        }

        // Generate the html thumbnail
        $html = view('blog.thumbnail', ['post' => $this->post])->render();

        // Generate the file path
        $path = 'blog/' . Str::random(40) . '.webp';

        // Create the directory if it doesn't exist
        Storage::disk('public')->makeDirectory('blog');

        // Generate the thumbnail
        Browsershot::html($html)
            ->addChromiumArguments([
                'no-sandbox', 'disable-setuid-sandbox',
            ])
            ->waitUntilNetworkIdle()
            ->setScreenshotType('webp', 100)
            ->windowSize(640, 360)
            ->save(Storage::disk('public')->path($path));

        // Update the post thumbnail
        $this->post->update(['thumbnail' => $path]);
    }
}
