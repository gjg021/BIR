<?php

namespace App\Http\Middleware;

use App\Swep\Helpers\Get;
use Closure;
use Illuminate\Http\Request;

class VerifyEmailAddress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Get::setting('require_email_address')->int_value == 1){
            if(\Auth::user()->email == null){
                if($request->ajax()){
                    abort(503,'Please reload the page.');
                }
                return redirect('/verifyEmail?next='.$request->path());
            }
            return $next($request);
        }else{
            return  $next($request);
        }
    }
}
