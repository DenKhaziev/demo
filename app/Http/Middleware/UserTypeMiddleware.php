<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserTypeMiddleware
{
    public function handle(Request $request, Closure $next, $type)
    {
        $user = auth()->user();

        if ($type === 'parent' && !$user?->is_parent) {
            abort(403, 'Доступ только для родителей');
        }

        if ($type === 'admin' && !$user?->is_admin) {
            abort(403, 'Доступ только для админов');
        }

        return $next($request);
    }
}

