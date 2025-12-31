<?php

declare(strict_types=1);

use Illuminate\Http\Request;

Route::get('/ip', fn (Request $request) => response()->json(['ip' => $request->ip(), 'country' => $request->header('CF-IPCountry')]));
