<?php

namespace App\Http\Middleware;

use Closure;
use URL;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
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
        if(Auth::check())
        {
            return $next($request); 
            
        } 
      
      //根据openId获取用户信息，绑定过的用户可直接登录    
      //  dd($request->openId);
        
        
        return redirect(URL::action('AuthController@getLogin'));
    }
}
