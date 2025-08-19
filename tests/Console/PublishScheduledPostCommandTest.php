<?php

use App\Console\Commands\PublishScheduledPostsCommand;
use App\Models\Post;
use Illuminate\Support\Carbon;

it('doesnt publish scheduled post if date is future', function () {
    $this->travelTo(Carbon::create(2025, 8, 18, 8, 0));

    $post = Post::factory()->create(['published_at' => Carbon::create(2025, 8, 18, 8, 55)]);

    $this->assertFalse($post->refresh()->is_published);

    $this->artisan(PublishScheduledPostsCommand::class);

    $this->assertFalse($post->refresh()->is_published);
});

it('publishes scheduled post if date is past', function () {
    $this->travelTo(Carbon::create(2025, 8, 18, 9, 0));

    $post = Post::factory()->create(['published_at' => Carbon::create(2025, 8, 18, 8, 55)]);

    $this->assertFalse($post->refresh()->is_published);

    $this->artisan(PublishScheduledPostsCommand::class);

    $this->assertTrue($post->refresh()->is_published);
});
