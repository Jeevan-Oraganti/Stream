<?php

namespace App\Providers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('admin', function (User $user) {
            return in_array($user->id, [1, 9]);
        });

        Gate::define('delete-notice', function (User $user) {
            return $user->id === 1;
        });

        Blade::if('admin', function () {
            return request()->user()?->can('admin');
        });
    }
}
