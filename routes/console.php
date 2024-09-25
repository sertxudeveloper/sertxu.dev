<?php

use App\Console\Commands;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(Commands\PublishScheduledPostsCommand::class)->everyMinute();
Schedule::command(Commands\SitemapGenerateCommand::class)->daily();
Schedule::command(Commands\SqliteBackup::class)->daily();
Schedule::command(Commands\SqliteCompactDatabaseCommand::class, ['--connection' => 'sqlite'])->monthly();
