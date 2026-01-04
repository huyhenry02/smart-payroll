<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('auth.showLogin');
        }
        $routeName = optional($request->route())->getName() ?? '';
        if ($routeName === '') {
            return $next($request);
        }
        if ($user->roleInfo && $user->roleInfo->code === 'ROLE-0001') {
            return $next($request);
        }
        if (!$user->hasPermission($routeName)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            return redirect()->route('errors.403');
        }
        return $next($request);
    }
}
