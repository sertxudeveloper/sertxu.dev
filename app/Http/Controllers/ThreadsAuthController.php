<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Threads\Threads;
use Illuminate\Http\Request;

final class ThreadsAuthController
{
    /**
     * Redirect the user to the Threads authentication page.
     */
    public function index(Threads $threads)
    {
        return $threads->authenticate();
    }

    /**
     * Authenticate the user with Threads.
     */
    public function store(Request $request, Threads $threads)
    {
        $threads->authenticateCallback($request);

        return redirect()->route('home');
    }
}
