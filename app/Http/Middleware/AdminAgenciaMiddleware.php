<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminAgenciaMiddleware
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
        if(Auth::check()){
           $user = Auth::User();
           $isAdminAg = $user->adminUserAgencia()->where('excluido', null)->count();
           if (Auth::user()->tipo == 'agencia' && $isAdminAg > 0){
                return $next($request);
            }else{
                return redirect()->route('login')->with('error', 'Você precisa efetuar o login para continuar.');
            }
        }
    }
}
