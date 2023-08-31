<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified extends EnsureEmailIsVerified
{
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        $response = parent::handle($request, $next, $redirectToRoute);

        if (empty($request->user()->organization_verified_at)) {
                return $request->expectsJson()
                    ? abort(403, 'Your account is under verification by ' . $request->user()->organization?->name)
                    : redirect()->guest(route($redirectToRoute ?: 'verification.notice'));
        }

        return $response;
    }
}
