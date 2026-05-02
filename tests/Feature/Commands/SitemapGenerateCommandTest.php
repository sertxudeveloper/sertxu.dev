<?php

declare(strict_types=1);

use App\Console\Commands\SitemapGenerateCommand;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('r2');
});

it('generates a sitemap with static pages', function () {
    $this->artisan(SitemapGenerateCommand::class)
        ->assertSuccessful()
        ->expectsOutput('Sitemap generated and uploaded to R2 successfully.');

    expect(Storage::disk('r2')->exists('sitemap.xml'))->toBeTrue();

    $content = Storage::disk('r2')->get('sitemap.xml');
    expect($content)->toContain(route('home'))
        ->toContain(route('posts.index'))
        ->toContain(route('projects.index'));
});

it('includes published posts in the sitemap', function () {
    $post = Post::factory()->published()->create([
        'slug' => 'test-post',
    ]);

    $this->artisan(SitemapGenerateCommand::class)
        ->assertSuccessful();

    $content = Storage::disk('r2')->get('sitemap.xml');
    expect($content)->toContain(route('posts.show', $post->slug));
});

it('does not include unpublished posts in the sitemap', function () {
    Post::factory()->create([
        'is_published' => false,
        'slug' => 'draft-post',
    ]);

    $this->artisan(SitemapGenerateCommand::class)
        ->assertSuccessful();

    $content = Storage::disk('r2')->get('sitemap.xml');
    expect($content)->not->toContain(route('posts.show', 'draft-post'));
});

it('includes published projects in the sitemap', function () {
    $project = Project::factory()->published()->create([
        'slug' => 'test-project',
    ]);

    $this->artisan(SitemapGenerateCommand::class)
        ->assertSuccessful();

    $content = Storage::disk('r2')->get('sitemap.xml');
    expect($content)->toContain(route('projects.show', $project->slug));
});

it('writes sitemap to r2 disk', function () {
    $this->artisan(SitemapGenerateCommand::class)
        ->assertSuccessful();

    expect(Storage::disk('r2')->exists('sitemap.xml'))->toBeTrue();
});
