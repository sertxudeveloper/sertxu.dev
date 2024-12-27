<?php

declare(strict_types=1);

namespace App\Services\Threads;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

final readonly class Threads
{
    private string $app_id;

    private string $app_secret;

    private string $redirect_uri;

    private string $scope;

    private string $base_url;

    /**
     * Create a new Threads instance.
     */
    public function __construct()
    {
        $this->app_id = config('services.threads.app_id');
        $this->app_secret = config('services.threads.app_secret');
        $this->redirect_uri = config('services.threads.redirect_uri');
        $this->scope = 'threads_basic,threads_content_publish';
        $this->base_url = 'https://graph.threads.net/oauth';
    }

    /**
     * Redirect the user to the Threads authentication page.
     */
    public function authenticate(): RedirectResponse
    {
        $redirect = "https://threads.net/oauth/authorize?client_id={$this->app_id}&redirect_uri={$this->redirect_uri}&scope={$this->scope}&response_type=code";

        return redirect()->away($redirect);
    }

    /**
     * Authenticate the user with Threads.
     */
    public function authenticateCallback(Request $request): void
    {
        if ($request->has('error')) {
            abort(403, $request->get('error_description'));
        }

        if ($request->has('code')) {
            $this->requestShortLivedToken($request);
            $this->requestLongLivedToken($request);
        }
    }

    /**
     * Refresh the Threads access token.
     */
    public function refreshToken(User $user): void
    {
        $response = Http::post("{$this->base_url}/refresh_access_token", [
            'grant_type' => 'th_refresh_token',
            'access_token' => $user->threads_access_token,
        ]);

        if ($response->failed()) {
            abort(403, 'Failed to refresh Threads access token.');
        }

        $response = $response->json();

        $user->update([
            'threads_access_token' => $response['access_token'],
            'threads_access_token_expires_at' => now()->addSeconds($response['expires_in']),
        ]);
    }

    /**
     * Request a long-lived token from Threads.
     */
    private function requestLongLivedToken(Request $request): void
    {
        $response = Http::get("https://graph.threads.net/access_token", [
            'client_id' => $this->app_id,
            'client_secret' => $this->app_secret,
            'grant_type' => 'th_exchange_token',
            'access_token' => $request->user()->threads_access_token,
        ]);

        if ($response->failed()) {
            abort(403, $request->get('error_message'));
        }

        $response = $response->json();

        $request->user()->update([
            'threads_access_token' => $response['access_token'],
            'threads_access_token_expires_at' => now()->addSeconds($response['expires_in']),
        ]);
    }

    /**
     * Request a short-lived token from Threads.
     */
    private function requestShortLivedToken(Request $request): void
    {
        $authorizationCode = $request->get('code');

        $response = Http::get("{$this->base_url}/access_token", [
            'client_id' => config('services.threads.app_id'),
            'client_secret' => config('services.threads.app_secret'),
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('services.threads.redirect_uri'),
        ]);

        if ($response->failed()) {
            abort(403, $request->get('error_message'));
        }

        $response = $response->json();

        $request->user()->update([
            'threads_user_id' => $response['user_id'],
            'threads_access_token' => $response['access_token'],
            'threads_access_token_expires_at' => now()->addHour(),
        ]);
    }

    /**
     * Write a post to Threads.
     */
    public function writePost(User $user, string $content): void
    {
        $response = Http::post("https://graph.threads.net/v1.0/{$user->threads_user_id}/threads", [
            'media_type' => 'TEXT', // TEXT, IMAGE, VIDEO
            'text' => $content,
            'access_token' => $user->threads_access_token,
        ]);

        if ($response->failed()) {
            abort(403, 'Failed to write post to Threads.');
        }

        $mediaContainer = $response->json('id');

        $response = Http::post("https://graph.threads.net/v1.0/{$user->threads_user_id}/threads_publish", [
            'creation_id' => $mediaContainer,
            'access_token' => $user->threads_access_token,
        ]);

        if ($response->failed()) {
            abort(403, 'Failed to publish post to Threads.');
        }
    }
}
