<?php

declare(strict_types=1);

use App\Console\Commands;

Schedule::command(Commands\PublishScheduledPostsCommand::class)->everyMinute();
Schedule::command(Commands\SitemapGenerateCommand::class)->daily();
Schedule::command(Commands\SqliteBackupCommand::class)->daily();
Schedule::command(Commands\StorageBackupCommand::class)->daily();
Schedule::command(Commands\SqliteCompactDatabaseCommand::class, ['connection' => 'sqlite'])->monthly();
Schedule::command(Commands\ThreadsRefreshTokensCommand::class)->daily();
