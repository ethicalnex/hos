<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Doctor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== \App\Models\User::ROLE_DOCTOR) {
            abort(403, 'Access denied. Doctor privileges required.');
        }

        return $next($request);
    }
}