<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

final class TestMailCommand extends Command
{
    protected $signature = 'mail:test {--to=}';

    protected $description = 'Send a test email to admin users';

    public function handle(): int
    {
        $to = $this->option('to');

        if ($to) {
            Mail::to($to)->send(new TestMail($this->testBody()));

            $this->info("Test email sent to: {$to}");

            return Command::SUCCESS;
        }

        $admins = User::query()
            ->where('is_admin', true)
            ->get(['email', 'name']);

        if ($admins->isEmpty()) {
            $this->warn('No admin users found.');

            return Command::SUCCESS;
        }

        foreach ($admins as $admin) {
            Mail::to($admin->email, $admin->name)->send(new TestMail($this->testBody()));

            $this->info("Test email sent to: {$admin->email}");
        }

        return Command::SUCCESS;
    }

    private function testBody(): string
    {
        $timestamp = Carbon::now()->toDateTimeString();

        return <<<EOT
This is a test email from your application.

If you received this, your mail configuration is working correctly.

Sent at: {$timestamp}
EOT;
    }
}
