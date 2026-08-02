<?php

declare(strict_types=1);

use App\Models\Post;
use App\Models\Project;

it('can load home page', function (): void {
    $this->get('/')
        ->assertOk();
});

it('shows published posts ordered by published_at descending', function (): void {
    Post::factory()->published()->create(['title' => 'Oldest Post', 'published_at' => now()->subDays(2)]);
    Post::factory()->published()->create(['title' => 'Newest Post', 'published_at' => now()->subDay()]);
    Post::factory()->create(['title' => 'Draft Post', 'is_published' => false]);

    $this->get('/')
        ->assertOk()
        ->assertSeeTextInOrder(['Newest Post', 'Oldest Post'])
        ->assertDontSeeText('Draft Post');
});

it('limits the home page to the 6 most recent posts', function (): void {
    collect(range(1, 8))->each(fn (int $i) => Post::factory()->published()->create([
        'title' => "Home Post {$i}",
        'published_at' => now()->subMinutes($i),
    ]));

    $this->get('/')
        ->assertOk()
        ->assertSeeText('Home Post 1')
        ->assertDontSeeText('Home Post 7');
});

it('does not show unpublished projects on the home page', function (): void {
    Project::factory()->create(['title' => 'Hidden Project', 'is_published' => false]);

    $this->get('/')
        ->assertOk()
        ->assertDontSeeText('Hidden Project');
});

it('shows featured projects before regular ones on the home page', function (): void {
    Project::factory()->published()->create([
        'title' => 'Regular Home Project',
        'is_featured' => false,
        'created_at' => now()->subDay(),
    ]);

    Project::factory()->published()->create([
        'title' => 'Featured Home Project',
        'is_featured' => true,
        'created_at' => now()->subHours(2),
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSeeTextInOrder(['Featured Home Project', 'Regular Home Project']);
});

it('limits the home page to the 3 most recent projects', function (): void {
    collect(range(1, 5))->each(fn (int $i) => Project::factory()->published()->create([
        'title' => "Home Project {$i}",
        'is_featured' => false,
        'created_at' => now()->subHours($i),
    ]));

    $this->get('/')
        ->assertOk()
        ->assertSeeText('Home Project 1')
        ->assertDontSeeText('Home Project 4');
});
