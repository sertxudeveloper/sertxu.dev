<?php

declare(strict_types=1);

use App\Models\Experience;

it('can load experience index page', function (): void {
    Experience::factory()->create(['title' => 'Experience A', 'started_at' => '2019-01-01', 'ended_at' => '2019-01-02']);
    Experience::factory()->create(['title' => 'Experience B', 'started_at' => '2019-06-02', 'ended_at' => '2019-06-03']);
    Experience::factory()->create(['title' => 'Experience C', 'started_at' => '2019-06-03', 'ended_at' => null]);

    $this->get('/experience')
        ->assertOk()
        ->assertSeeText('My Experience')
        ->assertSeeTextInOrder([
            'Experience C',
            'Experience B',
            'Experience A',
        ]);
});
