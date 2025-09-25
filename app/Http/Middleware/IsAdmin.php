<?php

namespace App\Http\Middleware;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Используем accessor is_admin
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Нет доступа — только для администраторов');
        }

        return $next($request);
    }
}


