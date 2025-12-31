<?php

declare(strict_types=1);

it('can get the client IP address behind a trusted proxy', function (): void {
    $response = $this->get('/api/ip', [
        'X-Forwarded-For' => '203.0.113.123',
        'CF-IPCountry' => 'US',
    ]);

    $response->assertOk();
    $response->assertJson([
        'ip' => '203.0.113.123',
        'country' => 'US',
    ]);
});
