<?php

declare(strict_types=1);

use App\Models\Education;

it('can load education index page', function (): void {
    Education::factory()->create(['title' => 'Education A', 'started_at' => '2019-01-01', 'ended_at' => '2019-01-02']);
    Education::factory()->create(['title' => 'Education B', 'started_at' => '2019-06-02', 'ended_at' => '2019-06-03']);
    Education::factory()->create(['title' => 'Education C', 'started_at' => '2019-06-03', 'ended_at' => null]);

    $this->get('/education')
        ->assertOk()
        ->assertSeeText('My Education')
        ->assertSeeTextInOrder([
            'Education C',
            'Education B',
            'Education A',
        ]);
});
