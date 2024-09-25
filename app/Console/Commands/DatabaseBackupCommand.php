<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DatabaseBackupCommand extends Command
{
    protected $signature = 'database:backup';

    protected $description = 'Backup the database';

    public function handle(): void
    {
        $this->info('Backing up database...');

        $filename = 'backup-' . date('Y-m-d-H-i-s') . '.sqlite';
        copy(database_path('database.sqlite'), storage_path('app/backups/' . $filename));

        // Keep only the 5 most recent backups
        $backups = collect(scandir(storage_path('app/backups')))
            ->filter(fn($file) => pathinfo($file, PATHINFO_EXTENSION) === 'sqlite')
            ->sortByDesc(fn($file) => filemtime(storage_path('app/backups/' . $file)))
            ->values()
            ->slice(5);

        $backups->each(fn($file) => unlink(storage_path('app/backups/' . $file)));

        $this->info('Database backup complete!');
    }
}
