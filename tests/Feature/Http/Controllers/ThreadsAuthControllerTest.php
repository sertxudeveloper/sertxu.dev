<?php

declare(strict_types=1);

use App\Models\User;

it('redirects to Threads auth page when authenticated', function () {
    $user = User::factory()->create();

    config()->set('services.threads.app_id', 'app_id');
    config()->set('services.threads.app_secret', 'secret');
    config()->set('services.threads.redirect_uri', 'https://example.com/callback');

    $this->actingAs($user)
        ->get('/threads/auth')
        ->assertRedirect('https://threads.net/oauth/authorize?client_id=app_id&redirect_uri=https://example.com/callback&scope=threads_basic,threads_content_publish&response_type=code');
});

it('handles Threads callback without code and redirects home', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/threads/callback')
        ->assertRedirect('/');
});
