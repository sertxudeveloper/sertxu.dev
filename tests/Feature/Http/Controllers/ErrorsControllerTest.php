<?php

declare(strict_types=1);

it('loads the custom error pages', function (string $errorCode) {
    $this->get("/$errorCode")
        ->assertOk()
        ->assertViewIs("errors.$errorCode");
})->with(['401', '402', '403', '404', '418', '419', '429', '500', '503']);
