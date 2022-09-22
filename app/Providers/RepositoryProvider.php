<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Auth
		$this->app->bind("App\Interfaces\AuthInterface","App\Repositories\AuthRepository");
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
