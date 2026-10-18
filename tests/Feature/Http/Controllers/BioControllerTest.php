<?php

declare(strict_types=1);

it('can load bio index page', function (): void {
    $this->get('/bio')
        ->assertOk()
        ->assertSeeText('Sergio Peris')
        ->assertSeeText('My website')
        ->assertSeeText('TikTok');
});
