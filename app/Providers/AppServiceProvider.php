<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public static function redirectTo()
    {
    $user = auth()->user();

    if ($user && $user->role === 'admin') {
        return '/admin/dashboard';
    }

    return '/dashboard';
}


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        if (env('APP_ENV') === 'production' || str_contains(request()->header('x-forwarded-Photo', ''), 'https')){
            URL::forceScheme(('https'));
        }

    }
}
