<?php

use App\Models\Education;

it('can visit the education page', function () {
    $models = Education::factory(5)->create();

    $response = $this->get(route('education.index'));

    $response->assertOk();

    $response->assertSee($models->first()->title);
    $response->assertSee($models->first()->description);
});
