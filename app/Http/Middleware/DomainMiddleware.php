<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DomainMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $domain = null): Response
    {
        $currentDomain = $request->getHost();
        $userDomain = env('USER_DOMAIN', 'user.inventaris.local');
        $adminDomain = env('ADMIN_DOMAIN', 'admin.inventaris.local');

        // Set domain type in session for easier access
        if (str_contains($currentDomain, $userDomain) || str_contains($currentDomain, 'localhost:80')) {
            session(['domain_type' => 'user']);
            config(['app.domain_type' => 'user']);
        } elseif (str_contains($currentDomain, $adminDomain) || str_contains($currentDomain, 'localhost:8080')) {
            session(['domain_type' => 'admin']);
            config(['app.domain_type' => 'admin']);
        }

        // Block admin routes on user domain
        if (session('domain_type') === 'user') {
            if ($request->is('admin*') || $request->is('filament*')) {
                abort(404);
            }
        }

        // Block frontend routes on admin domain
        if (session('domain_type') === 'admin') {
            if ($request->is('frontend*') || $request->is('register*')) {
                abort(404);
            }
            
            // Redirect root to admin panel
            if ($request->is('/')) {
                return redirect('/admin');
            }
        }

        return $next($request);
    }
}
