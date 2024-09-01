<?php

use App\Models\Experience;

it('can visit the experience page', function () {
    $models = Experience::factory(5)->create();

    $response = $this->get(route('experience.index'));

    $response->assertOk();

    $response->assertSee($models->first()->title);
    $response->assertSee($models->first()->description);
});
