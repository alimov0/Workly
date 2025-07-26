<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        
        if (auth()->check() && auth()->user()->role === 'employer') {
            return $next($request);
        }

        abort(403, 'Sizga bu sahifaga kirishga ruxsat yo‘q.');
    }
}
