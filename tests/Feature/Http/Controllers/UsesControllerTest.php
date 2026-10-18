<?php

declare(strict_types=1);

it('can load uses index page', function (): void {
    $this->get('/uses')
        ->assertOk()
        ->assertSeeText('Core stack')
        ->assertSeeText('Data you can trust')
        ->assertSeeText('Infrastructure that doesn\'t wake you at 3 AM');
});
