<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    protected array $except = [
        'login',
        'api/auth/login',
        'api/front-page',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (setting('maintenance_mode', false)) {
            foreach ($this->except as $path) {
                if ($request->is($path)) {
                    return $next($request);
                }
            }

            $user = $request->user();

            if (!$user || !$user->hasRole('Admin')) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => 'System is under maintenance. Please try again later.',
                        'maintenance_mode' => true,
                    ], 503);
                }

                abort(503);
            }
        }

        return $next($request);
    }
}
