<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ($request->order->user->id === $request->user()->id || $request->user()->is_admin)) {
            return $next($request);
        }

        $message = 'Access denied!';

        return $request->routeIs('api.*')
            ? \response()->json(['message' => $message], Response::HTTP_FORBIDDEN) // Status: 403
            : abort(Response::HTTP_FORBIDDEN, $message);
    }
}
