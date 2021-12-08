<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserLimited
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role != 'admin') {
            return redirect()->back()->withErrors(["خطای 403، شما اجازه دسترسی به این قسمت را ندارید."]);
        }
        return $next($request);
    }
}
