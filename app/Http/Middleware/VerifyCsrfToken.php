<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */

    //跳过微信端口
    protected $except = [
        'wechat'
    ];

   //禁用CSRF 
   public function handle($request, Closure $next)
   {
        return $next($request);
   }
}
