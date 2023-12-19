<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class StagingAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!App::environment('production'))
        {
            $user = Auth::user();
            if($user){
                if(!$request->is('dashboard') && !$this->hasCredential($user))
                    return redirect()->route('dashboard');
            }
        }
        return $next($request);
    }

    private function hasCredential($user)
    {
        $access_role = $user->access_role;
        if($access_role){
            return true;
        }
        return false;
    }
}
