<?php

it('can visit the home page', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});
