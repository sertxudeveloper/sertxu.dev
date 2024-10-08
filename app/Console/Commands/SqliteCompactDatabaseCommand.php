<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\SQLiteConnection;
use LogicException;

final class SqliteCompactDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sqlite:compact-database
                            {connection=sqlite : The connection to compact}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compact the SQLite database';

    /**
     * Execute the console command.
     */
    public function handle(DatabaseManager $manager): void
    {
        $this->compact(
            $this->getDatabase($manager, $connection = $this->argument('connection'))
        );

        $this->info("The '$connection' connection has been compacted.");
    }

    /**
     * Returns the Database Connection
     */
    private function getDatabase(DatabaseManager $manager, string $connection): ConnectionInterface
    {
        $db = $manager->connection($connection);

        // We will throw an exception if the database is not SQLite
        if (! $db instanceof SQLiteConnection) {
            throw new LogicException("The '$connection' connection must be sqlite, [{$db->getDriverName()}] given.");
        }

        return $db;
    }

    /**
     * Compacts the SQLite database
     */
    private function compact(ConnectionInterface $connection): bool
    {
        return $connection->statement('VACUUM;');
    }
}
