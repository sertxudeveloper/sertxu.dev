<?php

it('can visit the experience page', function () {
    $response = $this->get(route('experience.index'));

    $response->assertOk();
});
