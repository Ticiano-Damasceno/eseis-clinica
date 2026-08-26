<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$role): Response
    {
        if (! in_array($request->user()?->role, $role, true)) {
            abort(403, 'Você não tem permissão para acessar esta área.');
        }   
    
    return $next($request);
    }
}
