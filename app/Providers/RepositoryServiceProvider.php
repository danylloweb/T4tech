<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $this->app->bind(\App\Repositories\TeamRepository::class, \App\Repositories\TeamRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\PlayerRepository::class, \App\Repositories\PlayerRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\GameRepository::class, \App\Repositories\GameRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserTypeRepository::class, \App\Repositories\UserTypeRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        //:end-bindings:
    }
}
