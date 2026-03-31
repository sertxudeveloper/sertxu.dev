<?php

declare(strict_types=1);

use App\Filament\Resources\Posts\Pages\EditPost;
use App\Models\Post;
use App\Models\User;
use Filament\Actions\DeleteAction;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $post = Post::factory()->create();

    $this->get(route('filament.admin.resources.posts.edit', ['record' => $post->id]))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $post = Post::factory()->create();

    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.posts.edit', ['record' => $post->id]))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->assertSuccessful();
});

it('can load post data into the form', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->assertSchemaStateSet([
            'title' => $post->title,
            'slug' => $post->slug,
            'text' => $post->text,
            'is_published' => $post->is_published,
            'posted_on_twitter' => $post->posted_on_twitter,
            'posted_on_dev' => $post->posted_on_dev,
            'posted_on_threads' => $post->posted_on_threads,
        ]);
});

it('can update a post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();
    $newPostData = Post::factory()->make();

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->fillForm([
            'title' => $newPostData->title,
            'slug' => $newPostData->slug,
            'text' => $newPostData->text,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Post::class, [
        'id' => $post->id,
        'title' => $newPostData->title,
        'slug' => $newPostData->slug,
    ]);
});

it('can publish a post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['is_published' => false]);

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->fillForm([
            'title' => $post->title,
            'slug' => $post->slug,
            'text' => $post->text,
            'is_published' => true,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Post::class, [
        'id' => $post->id,
        'is_published' => true,
    ]);
});

it('can unpublish a post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->published()->create();

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->fillForm([
            'title' => $post->title,
            'slug' => $post->slug,
            'text' => $post->text,
            'is_published' => false,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Post::class, [
        'id' => $post->id,
        'is_published' => false,
    ]);
});

it('can set posted_on_twitter toggle', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['posted_on_twitter' => false]);

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->fillForm([
            'title' => $post->title,
            'slug' => $post->slug,
            'text' => $post->text,
            'posted_on_twitter' => true,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Post::class, [
        'id' => $post->id,
        'posted_on_twitter' => true,
    ]);
});

it('can set posted_on_dev toggle', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['posted_on_dev' => false]);

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->fillForm([
            'title' => $post->title,
            'slug' => $post->slug,
            'text' => $post->text,
            'posted_on_dev' => true,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Post::class, [
        'id' => $post->id,
        'posted_on_dev' => true,
    ]);
});

it('can set posted_on_threads toggle', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['posted_on_threads' => false]);

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->fillForm([
            'title' => $post->title,
            'slug' => $post->slug,
            'text' => $post->text,
            'posted_on_threads' => true,
        ])
        ->call('save')
        ->assertNotified();

    assertDatabaseHas(Post::class, [
        'id' => $post->id,
        'posted_on_threads' => true,
    ]);
});

it('can set published_at date', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['published_at' => null]);
    $newPublishDate = now()->addDays(7);

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->fillForm([
            'title' => $post->title,
            'slug' => $post->slug,
            'text' => $post->text,
            'published_at' => $newPublishDate,
        ])
        ->call('save')
        ->assertNotified();

    expect($post->fresh()->published_at->format('Y-m-d'))->toBe($newPublishDate->format('Y-m-d'));
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->fillForm([
            'title' => $post->title,
            'slug' => $post->slug,
            'text' => $post->text,
            ...$data,
        ])
        ->call('save')
        ->assertHasFormErrors($errors)
        ->assertNotNotified();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`slug` is required' => [['slug' => null], ['slug' => 'required']],
    '`text` is required' => [['text' => null], ['text' => 'required']],
]);

it('can soft delete a post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();

    livewire(EditPost::class, [
        'record' => $post->id,
    ])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    expect($post->fresh()->trashed())->toBeTrue();
});
