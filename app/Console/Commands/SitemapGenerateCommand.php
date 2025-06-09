<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\SitemapGenerator;

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
        SitemapGenerator::create(config('app.url'))
            ->writeToFile(storage_path('sitemap.xml'));

        Storage::disk('r2')->put('sitemap.xml', file_get_contents(storage_path('sitemap.xml')));

        unlink(storage_path('sitemap.xml'));

        $this->info('Sitemap generated and uploaded to R2 successfully.');
    }
}
