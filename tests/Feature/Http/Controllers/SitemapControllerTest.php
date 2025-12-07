<?php

declare(strict_types=1);

it('serves the sitemap.xml from the r2 disk', function () {
    Storage::fake('r2');
    Storage::disk('r2')->put('sitemap.xml', '<urlset></urlset>');

    $this->get('/sitemap.xml')
        ->assertOk()
        ->assertHeader('Content-Type', 'application/xml')
        ->assertSee('<urlset>', false);
});
