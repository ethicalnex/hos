<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HospitalAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. Hospital administrator privileges required.');
        }

        // Check subscription status
        $hospital = auth()->user()->hospital;
        if (!$hospital->hasActiveSubscription()) {
            return redirect()->route('hospital.subscription.index')
                ->withErrors('Your hospital subscription has expired. Please renew to continue.');
        }

        return $next($request);
    }
}