<?php

use App\Livewire\Posts;
use App\Models\Post;

it('can visit the blog page', function () {
    $response = $this->get(route('blog.index'));

    $response->assertOk();
});

it('can visit a blog post', function () {
    $post = Post::factory()->create();

    $response = $this->get(route('blog.show', $post));

    $response->assertOk();
    $response->assertSee($post->title);
    $response->assertSee($post->content);
});

it('can load more posts at index', function () {
    Post::factory(20)->sequence(fn ($sequence) => ['title' => "Post {$sequence->index}"])->create();

    Livewire::test(Posts::class)
        ->assertSee('Post 2')
        ->assertDontSee('Post 9')
        ->assertDontSee('Post 11')

        ->call('loadMore')
        ->assertSee('Post 2')
        ->assertSee('Post 9')
        ->assertDontSee('Post 19')

        ->call('loadMore')
        ->assertSee('Post 2')
        ->assertSee('Post 9')
        ->assertSee('Post 19');
});
