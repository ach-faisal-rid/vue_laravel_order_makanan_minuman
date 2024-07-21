<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AbleCreateUpdateItem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return response([
                'message' => 'Unauthorized'
            ], 401);
        }

        if($user->role_id != 4) {
            return response('you cannot access this function'
            , 403
            );
        }
        return $next($request);
    }
}
