<?php

declare(strict_types=1);

use App\Models\Education;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Education model', function () {
    describe('defaultOrder scope', function () {
        it('orders entries by started_at descending', function () {
            $older = Education::factory()->create(['started_at' => '2019-01-01']);
            $newer = Education::factory()->create(['started_at' => '2021-06-01']);

            $education = Education::defaultOrder()->get();

            expect($education->pluck('id')->toArray())->toBe([$newer->id, $older->id]);
        });

        it('orders a single entry', function () {
            $education = Education::factory()->create(['started_at' => '2020-01-01']);

            $result = Education::defaultOrder()->get();

            expect($result->pluck('id')->toArray())->toBe([$education->id]);
        });
    });
});
