<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\PublishPostAction;
use App\Models\Post;
use Illuminate\Console\Command;
use Override;

final class PublishScheduledPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    #[Override]
    protected $signature = 'blog:publish-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    #[Override]
    protected $description = 'Publish scheduled posts';

    /**
     * Execute the console command.
     */
    public function handle(PublishPostAction $publishPostAction): void
    {
        Post::query()
            ->whereScheduled()
            ->where('published_at', '<=', now())
            ->get()
            ->each(fn (Post $post) => $publishPostAction->execute($post));
    }
}
