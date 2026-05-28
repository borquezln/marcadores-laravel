<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class EnsureUserIsActive
{
    /**
     * @param  Closure(Request): (Response|RedirectResponse|SymfonyResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|SymfonyResponse
    {
        $user = $request->user();

        if ($user?->status === User::STATUS_ACTIVE) {
            return $next($request);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Tu cuenta todavia no esta habilitada para acceder.');
    }
}
