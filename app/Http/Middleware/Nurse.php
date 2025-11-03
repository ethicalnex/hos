<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Nurse
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== \App\Models\User::ROLE_NURSE) {
            abort(403, 'Access denied. Nurse privileges required.');
        }

        return $next($request);
    }
}