<?php

namespace NTI\Providers;

use Illuminate\Support\Facades\Schema;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        // serve https from remote server
        if(env('APP_ENV') == 'production')
        {
            $url->forceScheme('https');
        }

        // mysql database
        Schema::defaultStringLength(191);

        // bugsnag registration
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
			$this->app->register(DuskServiceProvider::class);
    }
    }
}
