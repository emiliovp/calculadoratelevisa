<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use App\CalUsersSessions;
use Closure;

class DoubleSession
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
        $tcsUsers = new CalUsersSessions;
        $tcsUsersActual = $tcsUsers->validateDoubleSession();
        
        if(Auth::user()->created_at == $tcsUsersActual) {
            return $next($request);
        }
        
        Auth::logout();
        return redirect()->route('login')->with('msjWarning', 'Al parecer alguien más se ha logueado a su cuenta.');
    }
}
