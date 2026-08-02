<?php

declare(strict_types=1);

use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Experience model', function () {
    describe('defaultOrder scope', function () {
        it('orders entries by started_at descending', function () {
            $older = Experience::factory()->create(['started_at' => '2019-01-01']);
            $newer = Experience::factory()->create(['started_at' => '2021-06-01']);

            $experiences = Experience::defaultOrder()->get();

            expect($experiences->pluck('id')->toArray())->toBe([$newer->id, $older->id]);
        });

        it('orders a single entry', function () {
            $experience = Experience::factory()->create(['started_at' => '2020-01-01']);

            $result = Experience::defaultOrder()->get();

            expect($result->pluck('id')->toArray())->toBe([$experience->id]);
        });
    });
});
