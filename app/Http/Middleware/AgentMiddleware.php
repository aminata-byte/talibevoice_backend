<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgentMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isAgent()) {
            return response()->json([
                'message' => 'Accès refusé. Vous devez être agent de terrain.'
            ], 403);
        }

        return $next($request);
    }
}
