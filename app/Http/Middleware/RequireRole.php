<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RequireRole
{
    /**
     * @param  Closure(Request): (Response|SymfonyResponse)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response|SymfonyResponse
    {
        $user = $request->user();

        if ($user !== null && in_array($user->role, $roles, true)) {
            return $next($request);
        }

        abort(403);
    }
}
