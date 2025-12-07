<?php

declare(strict_types=1);

it('can load uses index page', function (): void {
    $this->get('/uses')
        ->assertOk()
        ->assertSeeText('Uses')
        ->assertSeeText('What technologies I know?')
        ->assertSeeText('What software I use?');
});
