<?php

declare(strict_types=1);

it('can get the client IP address behind a trusted proxy', function (): void {
    $response = $this->get('/api/ip', [
        'X-Real-Ip' => '162.158.123.139',
        'X-Forwarded-For' => '151.248.21.129, 162.158.123.139',
        'CF-IPCountry' => 'US',
    ]);

    $response->assertOk();
    $response->assertJson([
        'ip' => '151.248.21.129',
        'country' => 'US',
    ]);
});
