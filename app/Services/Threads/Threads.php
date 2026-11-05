<?php

declare(strict_types=1);

namespace App\Services\Threads;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

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
        $appId = config('services.threads.app_id');
        $appSecret = config('services.threads.app_secret');
        $redirectUri = config('services.threads.redirect_uri');

        if (! is_string($appId) || ! is_string($appSecret) || ! is_string($redirectUri)) {
            throw new InvalidArgumentException('Threads configuration values must be strings.');
        }

        $this->app_id = $appId;
        $this->app_secret = $appSecret;
        $this->redirect_uri = $redirectUri;
        $this->scope = 'threads_basic,threads_content_publish';
        $this->base_url = 'https://graph.threads.net';
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
        abort_if($request->has('error'), 403, $this->safeErrorMessage($request->input('error_description'), 'Threads authentication failed.'));

        if ($request->has('code')) {
            $authorizationCode = $request->input('code');

            abort_if(! is_string($authorizationCode) || $authorizationCode === '', 403, 'Missing or invalid Threads authorization code.');

            $user = $request->user();

            abort_if(! ($user instanceof User), 403, 'An authenticated user is required.');

            $this->requestShortLivedToken($request, $authorizationCode, $user);
            $this->requestLongLivedToken($request, $user);
        }
    }

    /**
     * Refresh the Threads access token.
     */
    public function refreshToken(User $user): void
    {
        $accessToken = $user->threads_access_token;

        abort_if(! is_string($accessToken) || $accessToken === '', 403, 'Failed to refresh Threads access token.');

        $response = Http::get("{$this->base_url}/refresh_access_token", [
            'grant_type' => 'th_refresh_token',
            'access_token' => $accessToken,
        ]);

        abort_if($response->failed(), 403, 'Failed to refresh Threads access token.');

        $response = $response->json();
        $refreshedAccessToken = is_array($response) ? ($response['access_token'] ?? null) : null;
        $expiresIn = is_array($response) ? ($response['expires_in'] ?? null) : null;

        abort_if(! is_string($refreshedAccessToken) || $refreshedAccessToken === '', 403, 'Invalid Threads token response.');
        abort_if(! is_int($expiresIn) && ! is_float($expiresIn), 403, 'Invalid Threads token response.');

        $user->update([
            'threads_access_token' => $refreshedAccessToken,
            'threads_access_token_expires_at' => now()->addSeconds($expiresIn),
        ]);
    }

    /**
     * Write a post to Threads.
     */
    public function writePost(User $user, string $content, ?string $topicTag = null): void
    {
        $payload = [
            'media_type' => 'TEXT', // TEXT, IMAGE, VIDEO
            'text' => $content,
            'access_token' => $user->threads_access_token,
        ];

        if ($topicTag !== null) {
            $payload['topic_tag'] = $topicTag;
        }

        $response = Http::post("{$this->base_url}/v1.0/{$user->threads_user_id}/threads", $payload);

        abort_if($response->failed(), 403, 'Failed to write post to Threads.');

        $mediaContainer = $response->json('id');

        $response = Http::post("{$this->base_url}/v1.0/{$user->threads_user_id}/threads_publish", [
            'creation_id' => $mediaContainer,
            'access_token' => $user->threads_access_token,
        ]);

        abort_if($response->failed(), 403, 'Failed to publish post to Threads.');
    }

    /**
     * Request a long-lived token from Threads from a valid short-lived token.
     */
    private function requestLongLivedToken(Request $request, User $user): void
    {
        $accessToken = $user->threads_access_token;

        abort_if(! is_string($accessToken) || $accessToken === '', 403, 'Invalid user\'s Threads access token.');

        $response = Http::get("{$this->base_url}/access_token", [
            'grant_type' => 'th_exchange_token',
            'client_secret' => $this->app_secret,
            'access_token' => $accessToken,
        ]);

        abort_if($response->failed(), 403, $this->safeErrorMessage($request->input('error_message'), 'Failed to authenticate with Threads.'));

        $response = $response->json();
        $longLivedAccessToken = is_array($response) ? ($response['access_token'] ?? null) : null;
        $expiresIn = is_array($response) ? ($response['expires_in'] ?? null) : null;

        abort_if(! is_string($longLivedAccessToken) || $longLivedAccessToken === '', 403, 'Invalid Threads token response.');
        abort_if(! is_int($expiresIn) && ! is_float($expiresIn), 403, 'Invalid Threads token response.');

        $user->update([
            'threads_access_token' => $longLivedAccessToken,
            'threads_access_token_expires_at' => now()->addSeconds($expiresIn),
        ]);
    }

    /**
     * Request a short-lived token from Threads.
     */
    private function requestShortLivedToken(Request $request, string $authorizationCode, User $user): void
    {
        $response = Http::get("{$this->base_url}/oauth/access_token", [
            'client_id' => $this->app_id,
            'client_secret' => $this->app_secret,
            'code' => $authorizationCode,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirect_uri,
        ]);

        abort_if($response->failed(), 403, $this->safeErrorMessage($request->input('error_message'), 'Failed to authenticate with Threads.'));

        $response = $response->json();
        $threadsUserId = is_array($response) ? ($response['user_id'] ?? null) : null;
        $accessToken = is_array($response) ? ($response['access_token'] ?? null) : null;

        abort_if(! is_string($threadsUserId) || $threadsUserId === '', 403, 'Invalid Threads token response.');
        abort_if(! is_string($accessToken) || $accessToken === '', 403, 'Invalid Threads token response.');

        $user->update([
            'threads_user_id' => $threadsUserId,
            'threads_access_token' => $accessToken,
            'threads_access_token_expires_at' => now()->addHour(),
        ]);
    }

    private function safeErrorMessage(mixed $message, string $fallback): string
    {
        return is_string($message) ? $message : $fallback;
    }
}
