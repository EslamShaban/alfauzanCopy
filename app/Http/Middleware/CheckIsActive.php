<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsActive
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

        if( Auth::check()&& Auth::user()->block){

            Auth::logout();

            return redirect('/login')->withErrors('هذا المستخدم تم حظره برجاء التواصل مع الادارة');
        }

        return $next($request);
    }
}
