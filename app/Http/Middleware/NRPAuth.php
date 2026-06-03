<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NRPAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Custom authentication logic for NRP field
        if ($request->is('login') || $request->is('register')) {
            return $next($request);
        }

        if (Auth::check()) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
