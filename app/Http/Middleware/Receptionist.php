<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Receptionist
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== \App\Models\User::ROLE_RECEPTIONIST) {
            abort(403, 'Access denied. Receptionist privileges required.');
        }

        return $next($request);
    }
}