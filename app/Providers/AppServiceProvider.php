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
    public function boot(Kernel $kernel): void
    {
        Vite::prefetch(concurrency: 3);

        Model::unguard();

        Gate::define('admin', function (User $user) {
            return $user->name === 'Alen';
        });

        Gate::define('delete-notice', function (User $user, $notice) {
            return $user->name === 'Alen';
        });

        Blade::if('admin', function () {
            return request()->user()?->can('admin');
        });
    }
}
