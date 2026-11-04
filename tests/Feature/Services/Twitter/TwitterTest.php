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

it('allows null optional OAuth tokens in configuration', function () {
    config([
        'services.twitter.consumer_key' => 'consumer-key',
        'services.twitter.consumer_secret' => 'consumer-secret',
        'services.twitter.access_token' => null,
        'services.twitter.access_token_secret' => null,
    ]);

    expect(app(Twitter::class))->toBeInstanceOf(Twitter::class);
});

it('rejects non-string required Twitter configuration', function () {
    config([
        'services.twitter.consumer_key' => ['invalid'],
        'services.twitter.consumer_secret' => 'consumer-secret',
        'services.twitter.access_token' => null,
        'services.twitter.access_token_secret' => null,
    ]);

    expect(fn () => app(Twitter::class))->toThrow(InvalidArgumentException::class);
});

it('rejects non-string optional Twitter tokens', function () {
    config([
        'services.twitter.consumer_key' => 'consumer-key',
        'services.twitter.consumer_secret' => 'consumer-secret',
        'services.twitter.access_token' => ['invalid'],
        'services.twitter.access_token_secret' => null,
    ]);

    expect(fn () => app(Twitter::class))->toThrow(InvalidArgumentException::class);
});
