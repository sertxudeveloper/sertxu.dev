<?php

use App\Livewire\Posts;
use App\Models\Post;

it('can visit the blog page', function () {
    Post::withoutEvents(function () {
        Post::factory(3)->create();

        $response = $this->get(route('posts.index'));

        $response->assertOk();
    });
});

it('can visit a blog post', function () {
    Post::withoutEvents(function () {
        $post = Post::factory()->create();

        $response = $this->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertSee($post->title);
        $response->assertSee($post->content);
    });
});

it('can load more posts at index', function () {
    Post::withoutEvents(function () {
        Post::factory(20)
            ->sequence(fn ($sequence) => ['title' => "Post {$sequence->index}"])
            ->published()
            ->create();

        Livewire::test(Posts::class)
            ->assertSee('Post 19')
            ->assertDontSee('Post 9')
            ->assertDontSee('Post 2')

            ->call('loadMore')
            ->assertSee('Post 19')
            ->assertSee('Post 9')
            ->assertDontSee('Post 2')

            ->call('loadMore')
            ->assertSee('Post 19')
            ->assertSee('Post 9')
            ->assertSee('Post 2');
    });
});
