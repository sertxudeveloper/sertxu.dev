<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use App\Services\Threads\Threads;
use App\Services\Twitter\Twitter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\Skip;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\NoReturn;

final class PostToThreadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly Post $post) {}

    /**
     * Get the middleware the job should pass through.
     */
    public function middleware(): array
    {
        return [
            Skip::when(fn () => $this->post->posted_on_threads),
        ];
    }

    /**
     * Handle the job.
     */
    #[NoReturn]
    public function handle(Threads $threads): void
    {
        $user = User::query()->where('threads_user_id', '9093830107340733')->firstOrFail();

        $threads->writePost($user, $this->post->toThreads());

        $this->post->updateQuietly(['posted_on_threads' => true]);
    }
}
