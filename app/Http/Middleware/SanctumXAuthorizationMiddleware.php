<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanctumXAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $xAuthorization = $request->header('X-Authorization');

        if ($xAuthorization) {
            $request->headers->set('Authorization', $xAuthorization);
        }

        if ($request->isMethod('GET')) {
            $limit = $request->query->get('limit');
            if (is_numeric($limit)) {
                if ((int)$limit > 400) {
                    $request->query->set('limit', 400);
                }
            }
        }

        return $next($request);
    }
}

