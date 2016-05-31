<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param null $guard
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                /*return redirect()->guest('login');*/
                throw new \Exception("Нужно авторизоваться");
            }
        }
        if (!Auth::user()->isAdmin()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Нужны права администратора.', 401);
            } else {
                /*return redirect()->guest('login');*/
                throw new \Exception("Нужно авторизоваться админом");
            }
        }
        return $next($request);
    }
}
