<?php

namespace SteadfastCollective\ConvertKit\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AddRefererToSessionStorage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (! Str::contains($request->headers->get('referer'), $request->getHost())) {
            if (! $request->session()->has('referer')) {
                $request->session()->put(
                    'referer',
                    $request->headers->get('referer')
                );
            }
        }

        return $next($request);
    }
}
