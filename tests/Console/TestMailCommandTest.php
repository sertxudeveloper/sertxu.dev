<?php

declare(strict_types=1);

use App\Console\Commands\TestMailCommand;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('sends test mail to custom recipient', function () {
    Mail::fake();

    $this->artisan(TestMailCommand::class, ['--to' => 'test@example.com'])
        ->assertExitCode(0)
        ->expectsOutput('Test email sent to: test@example.com');

    Mail::assertSent(TestMail::class, function (TestMail $mail) {
        return $mail->hasTo('test@example.com');
    });

    Mail::assertSent(TestMail::class, function (TestMail $mail) {
        $mail->assertSeeInText('This is a test email from your application.');
        $mail->assertSeeInText('your mail configuration is working correctly');
        $mail->assertSeeInText('Sent at:');

        return true;
    });
});

it('sends test mail to all admin users', function () {
    Mail::fake();

    $admin = User::factory()->create(['is_admin' => true]);

    $this->artisan(TestMailCommand::class)
        ->assertExitCode(0)
        ->expectsOutput("Test email sent to: {$admin->email}");

    Mail::assertSent(TestMail::class, function (TestMail $mail) use ($admin) {
        return $mail->hasTo($admin->email);
    });

    Mail::assertSent(TestMail::class, function (TestMail $mail) {
        $mail->assertSeeInText('This is a test email from your application.');
        $mail->assertSeeInText('your mail configuration is working correctly');
        $mail->assertSeeInText('Sent at:');

        return true;
    });
});

it('warns when no admin users exist', function () {
    Mail::fake();

    $this->artisan(TestMailCommand::class)
        ->assertExitCode(0)
        ->expectsOutput('No admin users found.');

    Mail::assertNothingSent();
});

it('sends test mail to multiple admin users', function () {
    Mail::fake();

    $admin1 = User::factory()->create(['is_admin' => true, 'email' => 'admin1@example.com']);
    $admin2 = User::factory()->create(['is_admin' => true, 'email' => 'admin2@example.com']);
    $nonAdmin = User::factory()->create(['is_admin' => false, 'email' => 'user@example.com']);

    $this->artisan(TestMailCommand::class)
        ->assertExitCode(0)
        ->expectsOutput('Test email sent to: admin1@example.com')
        ->expectsOutput('Test email sent to: admin2@example.com');

    Mail::assertSent(TestMail::class, function (TestMail $mail) use ($admin1) {
        return $mail->hasTo($admin1->email);
    });

    Mail::assertSent(TestMail::class, function (TestMail $mail) use ($admin2) {
        return $mail->hasTo($admin2->email);
    });

    Mail::assertNotSent(TestMail::class, function (TestMail $mail) use ($nonAdmin) {
        return $mail->hasTo($nonAdmin->email);
    });
});
