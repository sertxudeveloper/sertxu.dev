<?php

declare(strict_types=1);

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\Twitter;

it('posts to twitter api with correct text', function () {
    $mock = Mockery::mock(TwitterOAuth::class);
    $mock->shouldReceive('post')
        ->once()
        ->with('tweets', ['text' => 'Hello world'])
        ->andReturn(['data' => ['id' => '123']]);

    $twitter = new Twitter($mock);
    $result = $twitter->tweet('Hello world');

    expect($result)->toHaveKey('data.id', '123');
});

it('returns array response from api', function () {
    $mock = Mockery::mock(TwitterOAuth::class);
    $mock->shouldReceive('post')
        ->once()
        ->andReturn([
            'data' => [
                'id' => '456',
                'text' => 'Test tweet',
            ],
        ]);

    $twitter = new Twitter($mock);
    $result = $twitter->tweet('Test tweet');

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('data')
        ->and($result['data'])->toHaveKey('id', '456');
});
