<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        Inertia::share([
            'flash' => function () {
                return [
                    'student_credentials' => session()->get('student_credentials'),
                    'success' => session()->get('success'),
                    'error' => session()->get('error'),
                ];
            },
            'auth' => function () {
                $user = Auth::guard('web')->user() ?? Auth::guard('child')->user();

                return $user ? [
                    'id' => $user->id,
                    'name' => $user->name ?? null,
                    'is_admin' => $user->is_admin ?? false, // или (bool) $user->is_admin
                ] : null;
            },

        ]);
    }
}
