<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiToken
{
    /**
     * Handle an incoming request and add additional header.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->headers->get('Authorization') === env('AUTH_TOKEN', 'Bearer 42gA1S5'))
            return $next($request);

        return response()->json([
            'message' => 'Unauthorized to access API',
            'data' => [],
        ], 401);
    }
}
