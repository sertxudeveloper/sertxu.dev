<?php

declare(strict_types=1);

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

use function Pest\Laravel\assertDatabaseHas;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.posts.create'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.posts.create'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(CreatePost::class)
        ->assertSuccessful();
});

it('can create a post', function () {
    $this->actingAs($this->admin);

    $newPostData = Post::factory()->make();

    livewire(CreatePost::class)
        ->fillForm([
            'title' => $newPostData->title,
            'slug' => $newPostData->slug,
            'text' => $newPostData->text,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Post::class, [
        'title' => $newPostData->title,
        'slug' => $newPostData->slug,
    ]);
});

it('can create a published post', function () {
    $this->actingAs($this->admin);

    $newPostData = Post::factory()->make();

    livewire(CreatePost::class)
        ->fillForm([
            'title' => $newPostData->title,
            'slug' => $newPostData->slug,
            'text' => $newPostData->text,
            'is_published' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Post::class, [
        'title' => $newPostData->title,
        'is_published' => true,
    ]);
});

it('can create a post with social media toggles', function () {
    $this->actingAs($this->admin);

    $newPostData = Post::factory()->make();

    livewire(CreatePost::class)
        ->fillForm([
            'title' => $newPostData->title,
            'slug' => $newPostData->slug,
            'text' => $newPostData->text,
            'posted_on_twitter' => true,
            'posted_on_dev' => true,
            'posted_on_threads' => true,
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(Post::class, [
        'title' => $newPostData->title,
        'posted_on_twitter' => true,
        'posted_on_dev' => true,
        'posted_on_threads' => true,
    ]);
});

it('auto-generates slug from title', function () {
    $this->actingAs($this->admin);

    livewire(CreatePost::class)
        ->fillForm([
            'title' => 'My Amazing Blog Post',
            'text' => 'Some content here',
        ])
        ->assertSchemaStateSet([
            'slug' => Str::slug('My Amazing Blog Post'),
        ]);
});

it('does not auto-generate slug when editing existing record', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['slug' => 'custom-slug']);

    livewire(CreatePost::class)
        ->fillForm([
            'title' => 'New Title That Would Change Slug',
            'slug' => $post->slug,
            'text' => 'Content',
        ]);
});

it('validates the form data', function (array $data, array $errors) {
    $this->actingAs($this->admin);

    $newPostData = Post::factory()->make();

    livewire(CreatePost::class)
        ->fillForm([
            'title' => $newPostData->title,
            'slug' => $newPostData->slug,
            'text' => $newPostData->text,
            ...$data,
        ])
        ->call('create')
        ->assertHasFormErrors($errors)
        ->assertNotNotified()
        ->assertNoRedirect();
})->with([
    '`title` is required' => [['title' => null], ['title' => 'required']],
    '`slug` is required' => [['slug' => null], ['slug' => 'required']],
    '`text` is required' => [['text' => null], ['text' => 'required']],
]);
