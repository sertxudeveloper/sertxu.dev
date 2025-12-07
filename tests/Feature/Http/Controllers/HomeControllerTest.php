<?php

declare(strict_types=1);

it('can load home page', function (): void {
    $this->get('/')
        ->assertOk();
});
