<?php

declare(strict_types=1);

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

Route::get('/ip', function (Request $request): JsonResponse {
    return response()->json([
        'ip' => $request->ip(),
        'scheme' => $request->getScheme(),
        'secure' => $request->isSecure(),
        'host' => $request->getHost(),
        'forwarded_proto' => $request->header('x-forwarded-proto'),
        'cloudflare_ip' => $request->header('cf-connecting-ip'),
        'country' => $request->header('CF-IPCountry'),
        'remote_addr' => request()->server('REMOTE_ADDR'),
    ]);
});
