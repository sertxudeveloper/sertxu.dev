<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\Cloudflare\Cloudflare;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use JetBrains\PhpStorm\NoReturn;

final class PurgeCacheContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly array $urls) {}

    /**
     * Handle the job.
     */
    #[NoReturn]
    public function handle(Cloudflare $cloudflare): void
    {
        $cloudflare->purgeCache($this->urls);
    }
}
