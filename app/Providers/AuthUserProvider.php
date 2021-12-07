<?php

namespace App\Providers;

use Auth;
use App\Providers\UserProvider;
use Illuminate\Support\ServiceProvider;

class AuthUserProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::provider('custom_provider', function ($app, array $config) {
			return new UserProvider();
		});
    }
}
