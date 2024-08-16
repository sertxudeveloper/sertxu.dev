<?php

it('can visit the education page', function () {
    $response = $this->get(route('education.index'));

    $response->assertOk();
});
