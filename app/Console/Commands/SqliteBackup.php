<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SqliteBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sqlite:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the SQLite database';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Backing up database...');

        $filename = 'backup-' . date('Y_m_d_His') . '.sqlite';
        copy(database_path('sqlite/database.sqlite'), storage_path('app/backups/' . $filename));

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
