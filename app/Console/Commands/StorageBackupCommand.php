<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

final class StorageBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the storage folder';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Backing up storage folder...');

        $filename = 'backup-'.date('Y_m_d_His').'.zip';

        $zip = new ZipArchive();
        if ($zip->open(storage_path('app/backups/'.$filename), ZipArchive::CREATE) === true) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(storage_path('app/public')),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);

                if (in_array(mb_substr($file, mb_strrpos($file, '/') + 1), ['.', '..'])) {
                    continue;
                }

                if (is_dir($file)) {
                    $zip->addEmptyDir(str_replace(storage_path('app/public').'/', '', $file));
                } elseif (is_file($file)) {
                    $zip->addFromString(str_replace(storage_path('app/public').'/', '', $file), file_get_contents($file));
                }
            }

            $zip->close();
        }
    }
}
