<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CaptureReferral
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref') && !session()->has('affiliate_ref')) {
            $code = strtoupper($request->ref);
            session()->put('affiliate_ref', $code);
        }

        return $next($request);
    }
}
