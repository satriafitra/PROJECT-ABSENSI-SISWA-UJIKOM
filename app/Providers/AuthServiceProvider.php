<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLE ADMIN
        |--------------------------------------------------------------------------
        */
        Gate::define('isAdmin', function ($user) {
            return $user->role === 'admin';
        });

        /*
        |--------------------------------------------------------------------------
        | ROLE GURU
        |--------------------------------------------------------------------------
        */
        Gate::define('isGuru', function ($user) {
            return $user->role === 'guru';
        });
    }
}
