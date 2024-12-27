<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Threads\Threads;
use Illuminate\Console\Command;

final class ThreadsRefreshTokensCommand extends Command
{
    /**
     * The console command signature.
     */
    protected $signature = 'threads:refresh-tokens';

    /**
     * The console command description.
     */
    protected $description = 'Refresh Threads access tokens that are about to expire.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $users = User::where('threads_access_token_expires_at', '<=', now()->days(10))->get();
        if ($users->isEmpty()) {
            return;
        }

        $this->info("Refreshing Threads access tokens for {$users->count()} users.");

        $users->each(fn ($user) => (new Threads())->refreshToken($user));

        $this->info('Tokens refreshed successfully.');
    }
}
