<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Cors
{
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('OPTIONS')) {
            $response = new Response('', 200);
        } else {
            $response = $next($request);
        }

        $response->header('Access-Control-Allow-Methods', 'GET, HEAD, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->header('Access-Control-Allow-Headers', 'Authorization, Content-Type');
        $response->header('Access-Control-Allow-Origin', '*');

        return $response;
    }
}
