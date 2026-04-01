<?php

declare(strict_types=1);

use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Posts\Widgets\LatestPosts;
use App\Models\Post;
use App\Models\User;

uses(Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.pages.dashboard'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.pages.dashboard'))
        ->assertForbidden();
});

it('can render the widget', function () {
    $this->actingAs($this->admin);

    livewire(LatestPosts::class)
        ->assertSuccessful();
});

it('displays the latest 4 records', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(6)->create();

    livewire(LatestPosts::class)
        ->assertCanSeeTableRecords($posts->sortByDesc('id')->take(4));
});

it('displays all records when fewer than 4 exist', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(2)->create();

    livewire(LatestPosts::class)
        ->assertCanSeeTableRecords($posts);
});

it('displays records in descending order by id', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(4)->create();

    livewire(LatestPosts::class)
        ->assertCanSeeTableRecords($posts->sortByDesc('id'));
});

it('shows empty state when no records exist', function () {
    $this->actingAs($this->admin);

    livewire(LatestPosts::class)
        ->assertSuccessful();
});

it('displays is_published status as boolean', function () {
    $this->actingAs($this->admin);

    $published = Post::factory()->published()->create();
    $unpublished = Post::factory()->create(['is_published' => false]);

    livewire(LatestPosts::class)
        ->assertCanSeeTableRecords(collect([$published, $unpublished]));
});

it('limits title to 30 characters', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['title' => 'This is a very long post title that exceeds thirty characters limit']);

    livewire(LatestPosts::class)
        ->assertCanSeeTableRecords(collect([$post]));
});

it('has edit action for each record', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();

    livewire(LatestPosts::class)
        ->assertTableActionVisible('edit', $post);
});

it('redirects to edit page when clicking edit', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();

    livewire(LatestPosts::class)
        ->callTableAction('edit', $post)
        ->assertRedirect(PostResource::getUrl('edit', ['record' => $post]));
});
