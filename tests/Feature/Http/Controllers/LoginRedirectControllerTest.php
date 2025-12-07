<?php

declare(strict_types=1);

it('redirects /login to /admin/login', function () {
    $this->get('/login')
        ->assertRedirect('/admin/login');
});
