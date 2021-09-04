<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isAdmin
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
        if ($request->user()->user_type == 0) {
            return $next($request);
        }
        Auth::logout();
        return redirect('/login')->withErrors(
            ['errors' => 'Your account has been end of session. Kindly please login again!']
        );
    }
}
