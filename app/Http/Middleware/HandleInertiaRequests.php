<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;
use Mockery\Matcher\Closure;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $child = Auth::guard('child')->user();
        $user = Auth::guard('web')->user() ?? Auth::guard('child')->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user,
                'id' => $user?->id,
                'is_child' => $child !== null,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
        ];
    }
}
