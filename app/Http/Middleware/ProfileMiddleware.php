<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles ): Response
    {
        try {

            $user = JWTAuth::parseToken()->authenticate();

            
            $allowedRoles = array_map('strtoupper', $roles);
    
            
            $profiles = $user->profiles->pluck('name')->map(function ($profile) {
                return strtoupper($profile);
            })->toArray();
    
            
            if (!array_intersect($allowedRoles, $profiles)) {
                return response()->json([
                    'error' => 'Unauthorized. Roles required: ' . implode(', ', $allowedRoles),               
                ], 403);
            }


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        return $next($request);

    }
}
