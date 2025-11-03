<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Hospital;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tenant identification for super admin routes and installer
        if ($this->shouldSkipTenantIdentification($request)) {
            return $next($request);
        }

        // Get hospital slug from subdomain or route parameter
        $hospitalSlug = $this->getHospitalSlug($request);

        if ($hospitalSlug) {
            $hospital = Hospital::where('slug', $hospitalSlug)->active()->first();

            if ($hospital) {
                // Store hospital in request and session
                $request->attributes->set('hospital', $hospital);
                session(['current_hospital' => $hospital]);
                
                // Set hospital_id for auth user if not super admin
                if (auth()->check() && !auth()->user()->isSuperAdmin()) {
                    auth()->user()->hospital_id = $hospital->id;
                }
                
                return $next($request);
            }
        }

        // If no hospital found and user is authenticated, redirect to super admin
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        }

        // Hospital not found
        abort(404, 'Hospital not found or inactive');
    }

    private function shouldSkipTenantIdentification(Request $request)
    {
        $skipPaths = [
            'install',
            'super-admin',
            'api',
            'login',
            'logout',
            'register',
        ];

        $currentPath = $request->path();

        foreach ($skipPaths as $path) {
            if (str_starts_with($currentPath, $path)) {
                return true;
            }
        }

        return false;
    }

    private function getHospitalSlug(Request $request)
    {
        // For localhost development, use path segments
        // Example: http://127.0.0.1:8000/hospital-slug/dashboard
        $segments = $request->segments();
        
        if (!empty($segments) && $segments[0] !== 'install' && $segments[0] !== 'super-admin') {
            return $segments[0];
        }

        // For production with subdomains
        $host = $request->getHost();
        $mainDomain = config('app.domain', 'ethicalnex.com');
        
        if (str_contains($host, $mainDomain)) {
            $subdomain = str_replace('.' . $mainDomain, '', $host);
            if ($subdomain !== $host && $subdomain !== 'www') {
                return $subdomain;
            }
        }

        return null;
    }
}