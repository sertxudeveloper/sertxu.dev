<?php

declare(strict_types=1);

use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Jobs\CreateOgImageJob;
use App\Models\Post;
use App\Models\User;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\assertSoftDeleted;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
    $this->user = User::factory()->create();
});

it('requires authentication', function () {
    $this->get(route('filament.admin.resources.posts.index'))
        ->assertRedirect(route('filament.admin.auth.login'));
});

it('denies access to non-admin users', function () {
    $this->actingAs($this->user)
        ->get(route('filament.admin.resources.posts.index'))
        ->assertForbidden();
});

it('can render the page', function () {
    $this->actingAs($this->admin);

    livewire(ListPosts::class)
        ->assertSuccessful();
});

it('can list posts', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(5)->create();

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords($posts);
});

it('can search posts by title', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(5)->create();

    $searchTerm = $posts->first()->title;

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->searchTable($searchTerm)
        ->assertCanSeeTableRecords($posts->where('title', $searchTerm));
});

it('can sort posts by title', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(5)->create();

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->sortTable('title')
        ->assertCanSeeTableRecords($posts);
});

it('can sort posts by published_at', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(5)->create();

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->sortTable('published_at')
        ->assertCanSeeTableRecords($posts);
});

it('can sort posts by is_published', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(5)->create();

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->sortTable('is_published')
        ->assertCanSeeTableRecords($posts);
});

it('can filter trashed posts', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();
    $post->delete();

    livewire(ListPosts::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$post]));
});

it('can bulk delete posts', function () {
    $this->actingAs($this->admin);

    $posts = Post::factory()->count(3)->create();

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->selectTableRecords($posts)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertNotified()
        ->assertCanNotSeeTableRecords($posts);

    $posts->each(fn (Post $post) => assertSoftDeleted($post));
});

it('can bulk restore trashed posts', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();
    $post->delete();

    livewire(ListPosts::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$post]))
        ->selectTableRecords(collect([$post]))
        ->callAction(TestAction::make(RestoreBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect($post->fresh()->trashed())->toBeFalse();
});

it('can bulk force delete trashed posts', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();
    $post->delete();

    livewire(ListPosts::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$post]))
        ->selectTableRecords(collect([$post]))
        ->callAction(TestAction::make(ForceDeleteBulkAction::class)->table()->bulk())
        ->assertNotified();

    expect(Post::find($post->id))->toBeNull();
});

it('can restore a soft deleted post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();
    $post->delete();

    livewire(ListPosts::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$post]))
        ->callTableAction('restore', $post)
        ->assertNotified();

    expect($post->fresh()->trashed())->toBeFalse();
});

it('can force delete a soft deleted post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();
    $post->delete();

    livewire(ListPosts::class)
        ->filterTable('trashed')
        ->assertCanSeeTableRecords(collect([$post]))
        ->callTableAction('forceDelete', $post)
        ->assertNotified();

    assertDatabaseMissing($post);
});

it('can soft delete a single post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create();

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords(collect([$post]))
        ->callTableAction('delete', $post)
        ->assertNotified();

    assertSoftDeleted($post);
});

it('can schedule a post for publishing', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['published_at' => null, 'is_published' => false]);

    livewire(ListPosts::class)
        ->callTableAction('schedule', $post);

    expect($post->fresh()->published_at)->not()->toBeNull();
});

it('does not schedule a post that already has a publish date', function () {
    $this->actingAs($this->admin);

    $publishDate = now()->addDays(5);
    $post = Post::factory()->create(['published_at' => $publishDate, 'is_published' => false]);

    livewire(ListPosts::class)
        ->assertTableActionHidden('schedule', $post);

    expect($post->fresh()->published_at->timestamp)->toBe($publishDate->timestamp);
});

it('can unschedule a post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['published_at' => now()->addDays(3), 'is_published' => false]);

    livewire(ListPosts::class)
        ->callTableAction('unschedule', $post);

    expect($post->fresh()->published_at)->toBeNull();
});

it('can publish a post now', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->create(['is_published' => false]);

    livewire(ListPosts::class)
        ->callTableAction('publish now', $post);

    assertDatabaseHas(Post::class, [
        'id' => $post->id,
        'is_published' => true,
    ]);
});

it('can unpublish a post', function () {
    $this->actingAs($this->admin);

    $post = Post::factory()->published()->create();

    livewire(ListPosts::class)
        ->callTableAction('unpublish', $post);

    assertDatabaseHas(Post::class, [
        'id' => $post->id,
        'is_published' => false,
    ]);

    expect($post->fresh()->published_at)->toBeNull();
});

it('can dispatch thumbnail generation for a single post', function () {
    Queue::fake();

    $this->actingAs($this->admin);

    $post = Post::factory()->create();

    livewire(ListPosts::class)
        ->assertActionExists(TestAction::make('generate thumbnail')->table($post));

    livewire(ListPosts::class)
        ->callAction(TestAction::make('generate thumbnail')->table($post));

    Queue::assertPushedTimes(CreateOgImageJob::class, 1);
    Queue::assertPushed(CreateOgImageJob::class, fn ($job) => $job->post->id === $post->id);
});

it('can bulk generate thumbnails', function () {
    Queue::fake();

    $this->actingAs($this->admin);

    $posts = Post::factory()->count(3)->create();

    livewire(ListPosts::class)
        ->assertActionExists(TestAction::make('generate thumbnails')->table()->bulk());

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords($posts)
        ->selectTableRecords($posts)
        ->callAction(TestAction::make('generate thumbnails')->table()->bulk());

    Queue::assertPushedTimes(CreateOgImageJob::class, 3);
});
