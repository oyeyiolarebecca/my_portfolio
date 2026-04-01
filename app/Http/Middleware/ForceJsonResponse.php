<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Ensure API requests always get JSON responses (no redirects / HTML).
     *
     * This is especially important for cross-origin fetch/axios calls that
     * may not send an explicit `Accept: application/json` header.
     *
     * @param  \Closure(\Illuminate\Http\Request): \Symfony\Component\HttpFoundation\Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        $request->setRequestFormat('json');

        return $next($request);
    }
}
