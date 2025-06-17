<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Spatie\Tags\Tag;

final class SitemapGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Sitemap::create()
            ->add(Url::create(route('home'))->setPriority(1)->setChangeFrequency(Url::CHANGE_FREQUENCY_ALWAYS))
            ->add(Url::create(route('education.index'))->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('experience.index'))->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('projects.index'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('uses'))->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY))
            ->add(Url::create(route('posts.index'))->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
            ->add(Post::query()->published()->get())
            ->add(Project::query()->get())
            ->add($this->createUrlPostTags())
            ->add($this->createUrlProjectTags())
            ->writeToDisk('r2', 'sitemap.xml', true);

        $this->info('Sitemap generated and uploaded to R2 successfully.');
    }

    private function createUrlPostTags(): array
    {
        $urls = [];

        Post::query()
            ->with('tags')
            ->published()
            ->get()
            ->pluck('tags')
            ->flatten()
            ->unique('id')
            ->map(function (Tag $tag) use (&$urls) {
                $urls[] = Url::create(route('posts.index', ['tag' => $tag->slug]))
                    ->setPriority(0.1)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);
            });

        return $urls;
    }

    private function createUrlProjectTags(): array
    {
        $urls = [];

        Project::query()
            ->with('tags')
            ->published()
            ->get()
            ->pluck('tags')
            ->flatten()
            ->unique('id')
            ->map(function (Tag $tag) use (&$urls) {
                $urls[] = Url::create(route('projects.index', ['tag' => $tag->slug]))
                    ->setPriority(0.1)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);
            });

        return $urls;
    }
}
