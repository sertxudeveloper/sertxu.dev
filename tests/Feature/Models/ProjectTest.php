<?php

declare(strict_types=1);

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Project model', function () {
    describe('isPublished', function () {
        it('returns true when project is published', function () {
            $project = Project::factory()->published()->create();

            expect($project->isPublished())->toBeTrue();
        });

        it('returns false when project is not published', function () {
            $project = Project::factory()->create(['is_published' => false]);

            expect($project->isPublished())->toBeFalse();
        });
    });

    describe('scopes', function () {
        it('returns only published projects with wherePublished scope', function () {
            $published = Project::factory()->published()->create();
            $unpublished = Project::factory()->create(['is_published' => false]);

            $projects = Project::wherePublished()->get();

            expect($projects->pluck('id')->contains($published->id))->toBeTrue()
                ->and($projects->pluck('id')->contains($unpublished->id))->toBeFalse();
        });

        it('orders by featured first then created_at with defaultOrder scope', function () {
            $featured = Project::factory()->published()->create([
                'is_featured' => true,
                'title' => 'Featured Project',
            ]);
            $regular = Project::factory()->published()->create([
                'is_featured' => false,
                'title' => 'Regular Project',
            ]);

            $projects = Project::defaultOrder()->get();

            $featuredIndex = $projects->pluck('title')->search('Featured Project');
            $regularIndex = $projects->pluck('title')->search('Regular Project');

            expect($featuredIndex)->toBeLessThan($regularIndex);
        });
    });

    describe('toSitemapTag', function () {
        it('generates a valid sitemap URL', function () {
            $project = Project::factory()->published()->create();

            $sitemapTag = $project->toSitemapTag();

            expect($sitemapTag->url)->toContain($project->slug)
                ->and($sitemapTag->priority)->toBe(0.1)
                ->and($sitemapTag->changeFrequency)->toBe('yearly');
        });
    });

    describe('registerMediaConversions', function () {
        it('registers thumbnail and poster conversions', function () {
            $project = Project::factory()->published()->create();

            $project->registerMediaConversions(null);

            $conversions = $project->mediaConversions;

            expect($conversions)->toHaveCount(2)
                ->and($conversions[0]->getName())->toBe('thumbnail')
                ->and($conversions[1]->getName())->toBe('poster');
        });
    });
});
