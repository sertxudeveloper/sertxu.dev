<?php

namespace App\Console\Commands;

use App\Actions\PublishPostAction;
use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPostsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:publish-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled posts';

    /**
     * Execute the console command.
     *
     * @param PublishPostAction $publishPostAction
     * @return void
     */
    public function handle(PublishPostAction $publishPostAction): void
    {
        Post::scheduled()->get()
            ->reject(fn (Post $post) => $post->published_at->isFuture())
            ->each(fn (Post $post) => $publishPostAction->execute($post));
    }
}
